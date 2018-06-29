<?php

    class ConstructrCMS extends ConstructrBase
    {
        public function beforeRoute($APP){
        	$APP->set('ACT_VIEW','pages');

            if ($APP->get('SESSION.login')=='true' && $APP->get('SESSION.username')!='' && $APP->get('SESSION.password')!=''){
                $APP->set('LOGIN_USER',$APP->get('DBCON')->exec(
                        ['SELECT * FROM constructr_backenduser WHERE constructr_user_active=:ACTIVE AND constructr_user_username=:USERNAME AND constructr_user_password=:PASSWORD LIMIT 1;'],
                        [[
                            ':ACTIVE'=>1,
                            ':USERNAME'=>$APP->get('SESSION.username'),
                            ':PASSWORD'=>$APP->get('SESSION.password')
                        ]]
                    )
                );

                $LOGIN_USER=$APP->get('LOGIN_USER');
                $LOGIN_USER_ID=$APP->get('LOGIN_USER.0.constructr_user_id');

                $APP->set('LOGIN_USER_RIGHTS',$APP->get('DBCON')->exec(
                        ['SELECT * FROM constructr_user_rights WHERE constructr_user_rights_user=:LOGIN_USER_ID;'],
                        [[':LOGIN_USER_ID'=>$LOGIN_USER_ID]]
                    )
                );

                $ITERATOR=new RecursiveIteratorIterator(new RecursiveArrayIterator($APP->get('LOGIN_USER_RIGHTS')));
                $i=1;
                $CLEAN_USER_RIGHTS=[];

                foreach ($ITERATOR as $VALUE){
                    if ($i==5){$i=1;}
                    if ($i==3){$MODUL_ID=$VALUE;}
                    if ($i==4){$RIGHT=$VALUE;}
                    $i++;
                    if ($i==5){$CLEAN_USER_RIGHTS[$MODUL_ID]=$RIGHT;}
                }

                $APP->set('LOGIN_USER_RIGHTS',$CLEAN_USER_RIGHTS);

                if (count($LOGIN_USER)!=1){
                    $APP->get('CONSTRUCTR_LOG')->write('USER NOT FOUND - USERNAME: '.$APP->get('SESSION.username'));
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/login-error');
                }
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
            }
        }

		/*TreeView of ConstructrPages*/
        public function tree_view($APP){
            $APP->set('MODUL_ID',10);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $APP->set('PAGES',$APP->get('DBCON')->exec(array('SELECT * FROM constructr_pages ORDER BY constructr_pages_order ASC;')));

			echo '<pre>';
			var_dump($APP->get('PAGES'));
			echo '</pre><hr>';

			$arrayCategories = array();

			foreach($APP->get('PAGES') as $PAGE){
 				$arrayCategories[$PAGE['constructr_pages_id']] = array("parent_id" => $PAGE['constructr_pages_mother'], "name" => $PAGE['constructr_pages_name']);
  			}

			echo '<pre>';
			var_dump($arrayCategories);
			echo '</pre><hr>';

			$TREE_MENU = self::createTreeView($arrayCategories, 0);

			echo '<style>
			img{border:none}input,select,textarea,th,td{font-size:1em}ol.tree{padding:0 0 0 30px;width:300px}li{position:relative;margin-left:-15px;list-style:none}li.file{margin-left:-1px!important}li.file a{background:url(document.png) 0 0 no-repeat;color:#fff;padding-left:21px;text-decoration:none;display:block}li.file a[href *= .pdf]{background:url(document.png) 0 0 no-repeat}li.file a[href *= .html]{background:url(document.png) 0 0 no-repeat}li.file a[href $= .css]{background:url(document.png) 0 0 no-repeat}li.file a[href $= .js]{background:url(document.png) 0 0 no-repeat}li input{position:absolute;left:0;margin-left:0;opacity:0;z-index:2;cursor:pointer;height:1em;width:1em;top:0}li input + ol{background:url(toggle-small-expand.png) 40px 0 no-repeat;margin:-.938em 0 0 -44px;height:1em}li input + ol > li{display:none;margin-left:-14px!important;padding-left:1px}li label{background:url(folder-horizontal.png) 15px 1px no-repeat;cursor:pointer;display:block;padding-left:37px}li input:checked + ol{background:url(toggle-small.png) 40px 5px no-repeat;margin:-1.25em 0 0 -44px;padding:1.563em 0 0 80px;height:auto}li input:checked + ol > li{display:block;margin:0 0 .125em}li input:checked + ol > li:last-child{margin:0 0 .063em}
			</style>';

			echo '<div id="content" class="general-style1">';
			echo $TREE_MENU;
			echo '</div>';

			die();
        }

		static public function createTreeView($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
			foreach ($array as $categoryId => $category){
				if ($currentParent == $category['parent_id']){
				    if ($currLevel > $prevLevel) echo " <ol class='tree'> ";
				    if ($currLevel == $prevLevel) echo " </li> ";
				    	echo '<li> <label for="subfolder2">'.$category['name'].'</label> <input type="checkbox" name="subfolder2"/>';
				    if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
				    	$currLevel++;
				    	self::createTreeView ($array, $categoryId, $currLevel, $prevLevel);
				    	$currLevel--;
			    }
			}
			if ($currLevel == $prevLevel) echo " </li>  </ol> ";
		}
		/*TreeView of ConstructrPages*/

        public function admin_init($APP){
            $APP->set('MODUL_ID',10);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $CSRF=parent::csrf();
            $APP->set('CSRF',$CSRF);
            $APP->set('SESSION.csrf',$CSRF);
            $ADDITIVE=parent::additive();
            $APP->set('ADDITIVE',$ADDITIVE);
            $APP->set('SESSION.additive',$ADDITIVE);
            $TRIPPLE_ADDITIVE=($ADDITIVE.$CSRF);
            $APP->set('TRIPPLE_ADDITIVE',$TRIPPLE_ADDITIVE);
            $APP->set('SESSION.tripple_additive',$TRIPPLE_ADDITIVE);
            $APP->set('PAGES',$APP->get('DBCON')->exec(array('SELECT constructr_pages_id FROM constructr_pages;')));
            $APP->set('PAGE_COUNTR',0);
            $APP->set('PAGE_COUNTR',count($APP->get('PAGES')));
            $APP->set('CONTENT',$APP->get('DBCON')->exec(array('SELECT constructr_content_id FROM constructr_content;')));
            $APP->set('CONTENT_COUNTR',0);
            $APP->set('CONTENT_COUNTR',count($APP->get('CONTENT')));
            $APP->set('USER',$APP->get('DBCON')->exec(array('SELECT constructr_user_id FROM constructr_backenduser;')));
            $APP->set('USER_COUNTR',0);
            $APP->set('USER_COUNTR',count($APP->get('USER')));

            $H=opendir($APP->get('UPLOADS'));

            $FILES=[];
            $i=0;

            while ($FILE=readdir($H)){
                if ($FILE!='.' && $FILE!='..'){
                    $i++;
                }
            }

            closedir($H);

            $APP->set('FILE_COUNTR',0);
            $APP->set('FILE_COUNTR',$i);

            echo Template::instance()->render('protected/TEMPLATES/constructr_admin.html','text/html');
        }

		public static function constructrNavGen($BASE_URL,$PAGES,$MOTHER=0){
	        $TREE='';
	        $TREE='<ul class="area-dragable" id="draggables">';
	        for($i=0,$ni=count($PAGES);$i<$ni;$i++){
	            if($PAGES[$i]['constructr_pages_mother']==$MOTHER){
	                $TREE.='<li class="dragger" draggable="true" data-page-id="'.$PAGES[$i]['constructr_pages_id'].'" data-page-level="'.$PAGES[$i]['constructr_pages_level'].'" data-page-mother="'.$PAGES[$i]['constructr_pages_mother'].'">';
	                $TREE.=$PAGES[$i]['constructr_pages_name'];
	                $TREE.=self::constructrNavGen($BASE_URL,$PAGES,$PAGES[$i]['constructr_pages_id']);
	                $TREE.='</li>';
	            }
	        }
	        $TREE.='</ul>';
			$TREE=str_replace('<ul class="area-dragable" id="draggables"></ul>','',$TREE);
	        return $TREE;
		}

		public static function get_max_page_level($APP){
            $APP->set('MAX_PAGE_LEVEL',$APP->get('DBCON')->exec(array('SELECT MAX(constructr_pages_level) AS MAX_LEVEL FROM constructr_pages;')));
			return $APP->get('MAX_PAGE_LEVEL.0.MAX_LEVEL');
		}

        public function page_management($APP){
            $APP->set('MODUL_ID',30);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            if(isset($_GET['mrcleaner'])){
                $APP->set('MRCLEANER',$_GET['mrcleaner']);
            } else {
                $APP->set('MRCLEANER','');
            }

            if(isset($_GET['edit'])){
                $APP->set('EDIT',$_GET['edit']);
            } else {
                $APP->set('EDIT','');
            }

            if(isset($_GET['new'])){
                $APP->set('NEW',$_GET['new']);
            } else {
                $APP->set('NEW','');
            }

            if(isset($_GET['delete'])){
                $APP->set('DELETE',$_GET['delete']);
            } else {
                $APP->set('DELETE','');
            }

            if(isset($_GET['move'])){
                $APP->set('MOVE',$_GET['move']);
            } else {
                $APP->set('MOVE','');
            }

            $APP->set('PAGES',$APP->get('DBCON')->exec(['SELECT * FROM constructr_pages ORDER BY constructr_pages_order ASC;']));

            echo Template::instance()->render('protected/TEMPLATES/constructr_admin_pagemanagement.html','text/html');
        }

        public function page_management_edit($APP){
            $APP->set('MODUL_ID',32);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $PAGE_ID=filter_var($APP->get('PARAMS.page_id'),FILTER_SANITIZE_NUMBER_INT);

            $CSRF=parent::csrf();
            $APP->set('CSRF',$CSRF);
            $APP->set('SESSION.csrf',$CSRF);

            $ADDITIVE=parent::additive();
            $APP->set('ADDITIVE',$ADDITIVE);
            $APP->set('SESSION.additive',$ADDITIVE);

            $TRIPPLE_ADDITIVE=($ADDITIVE.$CSRF);
            $APP->set('TRIPPLE_ADDITIVE',$TRIPPLE_ADDITIVE);
            $APP->set('SESSION.tripple_additive',$TRIPPLE_ADDITIVE);

            $APP->set('PAGE',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_id=:PAGE_ID LIMIT 1;'],
                    [[':PAGE_ID'=>$PAGE_ID]]
                )
            );

            $APP->set('ALLPAGES',$APP->get('DBCON')->exec(['SELECT * FROM constructr_pages ORDER BY constructr_pages_order ASC;']));

            $APP->set('TEMPLATES',array_diff(scandir(__DIR__.'/../../themes'),['..','.','.empty_file','ASSETS']));

            echo Template::instance()->render('protected/TEMPLATES/constructr_admin_pagemanagement_edit.html','text/html');
        }

        public function page_management_edit_verify($APP){
            $APP->set('MODUL_ID',32);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $POST_CSRF=$APP->get('POST.csrf');
            $POST_ADDITIVE=$APP->get('POST.csrf_additive');
            $POST_TRIPPLE_ADDITIVE=$APP->get('POST.csrf_tripple_additive');

            if ($POST_CSRF!=''){
                if ($POST_CSRF!=$APP->get('SESSION.csrf')){
                    $APP->get('CONSTRUCTR_LOG')->write('FORM CSRF DON\'T MATCH: '.$POST_USERNAME);
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
                }
            }

            if ($POST_ADDITIVE!=''){
                if ($POST_ADDITIVE!=$APP->get('SESSION.additive')){
                    $APP->get('CONSTRUCTR_LOG')->write('FORM ADDITIVE DON\'T MATCH: '.$POST_USERNAME);
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
                }
            }

            if ($POST_TRIPPLE_ADDITIVE!=''){
                if ($POST_TRIPPLE_ADDITIVE!=$APP->get('SESSION.tripple_additive')){
                    $APP->get('CONSTRUCTR_LOG')->write('FORM TRIPPLE ADDITIVE DON\'T MATCH: '.$POST_USERNAME);
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
                }
            }

            if ($POST_TRIPPLE_ADDITIVE!=$POST_ADDITIVE.$POST_CSRF){
                $APP->get('CONSTRUCTR_LOG')->write('FORM TRIPPLE ADDITIVE COMPARISON DON\'T MATCH: '.$POST_USERNAME);
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
            }

            $PAGE_DATETIME=date('Y-m-d H:i:s');
            $PAGE_ID=filter_var($APP->get('POST.edit_page'),FILTER_SANITIZE_NUMBER_INT);
            $PAGE_NAME=filter_var($APP->get('POST.page_name'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_EXT_URL=$APP->get('POST.page_ext_url');
            $PAGE_URL=filter_var($APP->get('POST.page_url'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_URL=self::cleanUrl($PAGE_URL);
			$PAGE_OLD_TEMPLATE=filter_var($APP->get('POST.old_template'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_TEMPLATE=filter_var($APP->get('POST.page_template'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_TITLE=filter_var($APP->get('POST.page_title'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_DESCRIPTION=filter_var($APP->get('POST.page_description'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_KEYWORDS=filter_var($APP->get('POST.page_keywords'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			if($APP->get('POST.page_css')!=''){
	            $PAGE_CSS=$APP->get('POST.page_css');
				$PAGE_CSS=CssMin::minify($APP->get('POST.page_css'));
				$PAGE_CSS_UNCOMPRESSED=$APP->get('POST.page_css');
			} else {
	            $PAGE_CSS='';
				$PAGE_CSS_UNCOMPRESSED='';
			}

			if($APP->get('POST.page_js')!=''){
	            $PAGE_JS=$APP->get('POST.page_js');
				$PAGE_JS=JSMin::minify($PAGE_JS);
				$PAGE_JS_UNCOMPRESSED=$APP->get('POST.page_js');
			} else {
	            $PAGE_JS='';
				$PAGE_JS_UNCOMPRESSED='';
			}

            $PAGE_VISIBILITY=filter_var($APP->get('POST.page_nav_visible'),FILTER_SANITIZE_NUMBER_INT);
            $SEARCHR=strripos($PAGE_URL,'/');
            $PAGE_ACTIVE=1;

            if ($SEARCHR!==false){
                if ($SEARCHR==(strlen($PAGE_URL)-1)){
                    $PAGE_URL=substr($PAGE_URL,0,($SEARCH-1));
                }
            }

            if ($PAGE_URL=='constructr'){
                $APP->set('EDIT','no-success');
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=no-success-cnstrctr');
            }

            $APP->set('URL_EXISTS',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_url=:PAGE_URL LIMIT 1;'],
                    [[':PAGE_URL'=>$PAGE_URL]]
                )
            );

            if (count($APP->get('URL_EXISTS'))>1){
                $APP->set('EDIT','no-success');
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=no-success-url');
            }

			if($PAGE_OLD_TEMPLATE!=$PAGE_TEMPLATE){
	            $APP->set('UPDATE_CONTENT_MAPPING',$APP->get('DBCON')->exec(
                    ['UPDATE constructr_content SET constructr_content_tpl_id_mapping=:NULLER WHERE constructr_content_page_id=:PAGE_ID;'],
                    [[
                        ':PAGE_ID'=>$PAGE_ID,
                        ':NULLER'=>''
                    ]]
                ));
			}

            $APP->set('UPDATE_PAGE',$APP->get('DBCON')->exec(
                    ['UPDATE constructr_pages SET constructr_pages_css=:PAGE_CSS,constructr_pages_css_uncompressed=:PAGE_CSS_UNCOMPRESSED,constructr_pages_js=:PAGE_JS,constructr_pages_js_uncompressed=:PAGE_JS_UNCOMPRESSED,constructr_pages_datetime=:PAGE_DATETIME,constructr_pages_name=:PAGE_NAME,constructr_pages_nav_visible=:PAGE_VISIBILITY,constructr_pages_url=:PAGE_URL,constructr_pages_ext_url=:PAGE_EXT_URL,constructr_pages_template=:PAGE_TEMPLATE,constructr_pages_title=:PAGE_TITLE,constructr_pages_description=:PAGE_DESCRIPTION,constructr_pages_keywords=:PAGE_KEYWORDS,constructr_pages_active=:PAGE_ACTIVE WHERE constructr_pages_id=:PAGE_ID LIMIT 1;'],
                    [[
                        ':PAGE_ID'=>$PAGE_ID,
                        ':PAGE_NAME'=>$PAGE_NAME,
                        ':PAGE_URL'=>$PAGE_URL,
                        ':PAGE_EXT_URL'=>$PAGE_EXT_URL,
                        ':PAGE_TEMPLATE'=>$PAGE_TEMPLATE,
                        ':PAGE_CSS'=>$PAGE_CSS,
                        ':PAGE_CSS_UNCOMPRESSED'=>$PAGE_CSS_UNCOMPRESSED,
                        ':PAGE_JS'=>$PAGE_JS,
                        ':PAGE_JS_UNCOMPRESSED'=>$PAGE_JS_UNCOMPRESSED,
                        ':PAGE_TITLE'=>$PAGE_TITLE,
                        ':PAGE_DESCRIPTION'=>$PAGE_DESCRIPTION,
                        ':PAGE_KEYWORDS'=>$PAGE_KEYWORDS,
                        ':PAGE_DATETIME'=>$PAGE_DATETIME,
                        ':PAGE_VISIBILITY'=>$PAGE_VISIBILITY,
                        ':PAGE_ACTIVE'=>$PAGE_ACTIVE
                    ]]
            ));

			parent::clean_up_cache($APP);

            $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=success');
        }

        public function page_management_new($APP){
            $APP->set('MODUL_ID',31);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $CSRF=parent::csrf();
            $APP->set('CSRF',$CSRF);
            $APP->set('SESSION.csrf',$CSRF);

            $ADDITIVE=parent::additive();
            $APP->set('ADDITIVE',$ADDITIVE);
            $APP->set('SESSION.additive',$ADDITIVE);

            $TRIPPLE_ADDITIVE=($ADDITIVE.$CSRF);
            $APP->set('TRIPPLE_ADDITIVE',$TRIPPLE_ADDITIVE);
            $APP->set('SESSION.tripple_additive',$TRIPPLE_ADDITIVE);

            $APP->set('PAGES',$APP->get('DBCON')->exec(['SELECT * FROM constructr_pages ORDER BY constructr_pages_order ASC;']));
            $APP->set('PAGE_COUNTR',count($APP->get('PAGES')));
            $APP->set('TEMPLATES',array_diff(scandir(__DIR__.'/../../themes'),['..','.','.empty_file','ASSETS']));

            echo Template::instance()->render('protected/TEMPLATES/constructr_admin_pagemanagement_new.html','text/html');
        }

        public function page_management_new_verify($APP){
            $APP->set('MODUL_ID',31);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $POST_CSRF=$APP->get('POST.csrf');
            $POST_ADDITIVE=$APP->get('POST.csrf_additive');
            $POST_TRIPPLE_ADDITIVE=$APP->get('POST.csrf_tripple_additive');

            if ($POST_CSRF!=''){
                if ($POST_CSRF!=$APP->get('SESSION.csrf')){
                    $APP->get('CONSTRUCTR_LOG')->write('FORM CSRF DON\'T MATCH: '.$POST_USERNAME);
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
                }
            }

            if ($POST_ADDITIVE!=''){
                if ($POST_ADDITIVE!=$APP->get('SESSION.additive')){
                    $APP->get('CONSTRUCTR_LOG')->write('FORM ADDITIVE DON\'T MATCH: '.$POST_USERNAME);
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
                }
            }

            if ($POST_TRIPPLE_ADDITIVE!=''){
                if ($POST_TRIPPLE_ADDITIVE!=$APP->get('SESSION.tripple_additive')){
                    $APP->get('CONSTRUCTR_LOG')->write('FORM TRIPPLE ADDITIVE DON\'T MATCH: '.$POST_USERNAME);
                    $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
                }
            }

            if ($POST_TRIPPLE_ADDITIVE!=$POST_ADDITIVE.$POST_CSRF){
                $APP->get('CONSTRUCTR_LOG')->write('FORM TRIPPLE ADDITIVE COMPARISON DON\'T MATCH: '.$POST_USERNAME);
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/logout');
            }

            $PAGES_COUNTR=filter_var($APP->get('POST.page_countr'),FILTER_SANITIZE_NUMBER_INT);
            $NEW_PAGE_ORDER=filter_var($APP->get('POST.page_order'),FILTER_SANITIZE_NUMBER_INT);
            $NEW_PAGE_ORDER_PAGE_ID=filter_var($APP->get('POST.page_order_page_id'),FILTER_SANITIZE_NUMBER_INT);
            $PAGE_DATETIME=date('Y-m-d H:i:s');
            $PAGE_NAME=filter_var($APP->get('POST.page_name'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_URL=filter_var($APP->get('POST.page_url'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_URL=self::cleanUrl($PAGE_URL);
			$PAGE_EXT_URL=$APP->get('POST.page_ext_url');
            $PAGE_TEMPLATE=filter_var($APP->get('POST.page_template'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_TITLE=filter_var($APP->get('POST.page_title'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_DESCRIPTION=filter_var($APP->get('POST.page_description'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $PAGE_KEYWORDS=filter_var($APP->get('POST.page_keywords'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			if($APP->get('POST.page_css')!=''){
	            $PAGE_CSS=$APP->get('POST.page_css');
				$PAGE_CSS=CssMin::minify($APP->get('POST.page_css'));
				$PAGE_CSS_UNCOMPRESSED=$APP->get('POST.page_css');
			} else {
	            $PAGE_CSS='';
				$PAGE_CSS_UNCOMPRESSED='';
			}

			if($APP->get('POST.page_js')!=''){
	            $PAGE_JS=$APP->get('POST.page_js');
				$PAGE_JS=JSMin::minify($PAGE_JS);
				$PAGE_JS_UNCOMPRESSED=$APP->get('POST.page_js');
			} else {
	            $PAGE_JS='';
				$PAGE_JS_UNCOMPRESSED='';
			}

            $PAGE_VISIBILITY=filter_var($APP->get('POST.page_nav_visible'),FILTER_SANITIZE_NUMBER_INT);
            $SEARCHR=strripos($PAGE_URL,'/');
            $PAGE_ACTIVE=1;

            if ($SEARCHR!==false){
                if ($SEARCHR==(strlen($PAGE_URL)-1)){
                    $PAGE_URL=substr($PAGE_URL,0,($SEARCH-1));
                }
            }

            if ($PAGE_URL=='constructr'){
                $APP->set('NEW','no-success');
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?new=no-success-cnstrctr');
            }

            $APP->set('URL_EXISTS',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_url=:PAGE_URL LIMIT 1;'],
                    [[':PAGE_URL'=>$PAGE_URL]]
                )
            );

            if (count($APP->get('URL_EXISTS'))!=0){
                $APP->set('NEW','no-success');
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?new=no-success-url');
            }

            if ($NEW_PAGE_ORDER==1 && $NEW_PAGE_ORDER_PAGE_ID!=''){
                $APP->set('RES',$APP->get('DBCON')->exec(
                        ['SELECT constructr_pages_order,constructr_pages_level,constructr_pages_mother FROM constructr_pages WHERE constructr_pages_id=:NEW_PAGE_ORDER_PAGE_ID LIMIT 1;'],
                        [[':NEW_PAGE_ORDER_PAGE_ID'=>$NEW_PAGE_ORDER_PAGE_ID]]
                    )
                );

                $APP->get('DBCON')->exec(
                    ['UPDATE constructr_pages SET constructr_pages_order=(constructr_pages_order + 1) WHERE constructr_pages_order >= :ACT_PAGE_ORDER;'],
                    [[':ACT_PAGE_ORDER'=>$APP->get('RES.0.constructr_pages_order')]]
                );

                $PAGE_LEVEL=$APP->get('RES.0.constructr_pages_level');
                $PAGE_MOTHER=$APP->get('RES.0.constructr_pages_mother');
                $PAGE_ORDER=$APP->get('RES.0.constructr_pages_order');

                $APP->set('CREATE_PAGE2',$APP->get('DBCON')->exec(
                        ['INSERT INTO constructr_pages SET constructr_pages_css=:PAGE_CSS,constructr_pages_css_uncompressed=:PAGE_CSS_UNCOMPRESSED,constructr_pages_js=:PAGE_JS,constructr_pages_js_uncompressed=:PAGE_JS_UNCOMPRESSED,constructr_pages_level=:PAGE_LEVEL,constructr_pages_mother=:PAGE_MOTHER,constructr_pages_order=:PAGE_ORDER,constructr_pages_datetime=:PAGE_DATETIME,constructr_pages_name=:PAGE_NAME,constructr_pages_nav_visible=:PAGE_VISIBILITY,constructr_pages_url=:PAGE_URL,constructr_pages_ext_url=:PAGE_EXT_URL,constructr_pages_template=:PAGE_TEMPLATE,constructr_pages_title=:PAGE_TITLE,constructr_pages_description=:PAGE_DESCRIPTION,constructr_pages_keywords=:PAGE_KEYWORDS,constructr_pages_active=:PAGE_ACTIVE;'],
                        [[
                            ':PAGE_LEVEL'=>$PAGE_LEVEL,
                            ':PAGE_MOTHER'=>$PAGE_MOTHER,
                            ':PAGE_ORDER'=>$PAGE_ORDER,
                            ':PAGE_NAME'=>$PAGE_NAME,
                            ':PAGE_URL'=>$PAGE_URL,
                            ':PAGE_EXT_URL'=>$PAGE_EXT_URL,
                            ':PAGE_TEMPLATE'=>$PAGE_TEMPLATE,
                            ':PAGE_CSS'=>$PAGE_CSS,
                            ':PAGE_CSS_UNCOMPRESSED'=>$PAGE_CSS_UNCOMPRESSED,
                            ':PAGE_JS'=>$PAGE_JS,
                            ':PAGE_JS_UNCOMPRESSED'=>$PAGE_JS_UNCOMPRESSED,
                            ':PAGE_TITLE'=>$PAGE_TITLE,
                            ':PAGE_DESCRIPTION'=>$PAGE_DESCRIPTION,
                            ':PAGE_KEYWORDS'=>$PAGE_KEYWORDS,
                            ':PAGE_DATETIME'=>$PAGE_DATETIME,
                            ':PAGE_VISIBILITY'=>$PAGE_VISIBILITY,
                            ':PAGE_ACTIVE'=>$PAGE_ACTIVE
						]]
                    )
                );
            } elseif ($NEW_PAGE_ORDER==2 && $NEW_PAGE_ORDER_PAGE_ID!=''){
                $APP->set('RES',$APP->get('DBCON')->exec(
                        ['SELECT * FROM constructr_pages WHERE constructr_pages_id=:NEW_PAGE_ORDER_PAGE_ID LIMIT 1;'],
                        [[':NEW_PAGE_ORDER_PAGE_ID'=>$NEW_PAGE_ORDER_PAGE_ID]]
                    )
                );

                $APP->get('DBCON')->exec(
                    ['UPDATE constructr_pages SET constructr_pages_order=(constructr_pages_order + 1) WHERE constructr_pages_order > :ACT_PAGE_ORDER;'],
                    [[':ACT_PAGE_ORDER'=>$APP->get('RES.0.constructr_pages_order')]]
                );

                $PAGE_LEVEL=($APP->get('RES.0.constructr_pages_level')+1);
                $PAGE_MOTHER=$APP->get('RES.0.constructr_pages_id');
                $PAGE_ORDER=($APP->get('RES.0.constructr_pages_order')+1);

                $APP->set('CREATE_PAGE3',$APP->get('DBCON')->exec(
                        ['INSERT INTO constructr_pages SET constructr_pages_css=:PAGE_CSS,constructr_pages_css_uncompressed=:PAGE_CSS_UNCOMPRESSED,constructr_pages_js=:PAGE_JS,constructr_pages_js_uncompressed=:PAGE_JS_UNCOMPRESSED,constructr_pages_level=:PAGE_LEVEL,constructr_pages_mother=:PAGE_MOTHER,constructr_pages_order=:PAGE_ORDER,constructr_pages_datetime=:PAGE_DATETIME,constructr_pages_name=:PAGE_NAME,constructr_pages_nav_visible=:PAGE_VISIBILITY,constructr_pages_url=:PAGE_URL,constructr_pages_ext_url=:PAGE_EXT_URL,constructr_pages_template=:PAGE_TEMPLATE,constructr_pages_title=:PAGE_TITLE,constructr_pages_description=:PAGE_DESCRIPTION,constructr_pages_keywords=:PAGE_KEYWORDS,constructr_pages_active=:PAGE_ACTIVE;'],
                        [[
                            ':PAGE_LEVEL'=>$PAGE_LEVEL,
                            ':PAGE_MOTHER'=>$PAGE_MOTHER,
                            ':PAGE_ORDER'=>$PAGE_ORDER,
                            ':PAGE_NAME'=>$PAGE_NAME,
                            ':PAGE_URL'=>$PAGE_URL,
                            ':PAGE_EXT_URL'=>$PAGE_EXT_URL,
                            ':PAGE_TEMPLATE'=>$PAGE_TEMPLATE,
                            ':PAGE_CSS'=>$PAGE_CSS,
                            ':PAGE_CSS_UNCOMPRESSED'=>$PAGE_CSS_UNCOMPRESSED,
                            ':PAGE_JS'=>$PAGE_JS,
                            ':PAGE_JS_UNCOMPRESSED'=>$PAGE_JS_UNCOMPRESSED,
                            ':PAGE_TITLE'=>$PAGE_TITLE,
                            ':PAGE_DESCRIPTION'=>$PAGE_DESCRIPTION,
                            ':PAGE_KEYWORDS'=>$PAGE_KEYWORDS,
                            ':PAGE_DATETIME'=>$PAGE_DATETIME,
                            ':PAGE_VISIBILITY'=>$PAGE_VISIBILITY,
                            ':PAGE_ACTIVE'=>$PAGE_ACTIVE
						]]
                    )
                );
            } elseif ($NEW_PAGE_ORDER==3 && $NEW_PAGE_ORDER_PAGE_ID!=''){
                $APP->set('RES',$APP->get('DBCON')->exec(
                        ['SELECT constructr_pages_order,constructr_pages_level,constructr_pages_mother FROM constructr_pages WHERE constructr_pages_id=:NEW_PAGE_ORDER_PAGE_ID LIMIT 1;'],
                        [[':NEW_PAGE_ORDER_PAGE_ID'=>$NEW_PAGE_ORDER_PAGE_ID]]
                    )
                );

                $APP->get('DBCON')->exec(
                    ['UPDATE constructr_pages SET constructr_pages_order=(constructr_pages_order + 1) WHERE constructr_pages_order > :ACT_PAGE_ORDER;'],
                    [[':ACT_PAGE_ORDER'=>$APP->get('RES.0.constructr_pages_order')]]
                );

                $PAGE_LEVEL=$APP->get('RES.0.constructr_pages_level');
                $PAGE_MOTHER=$APP->get('RES.0.constructr_pages_mother');
                $PAGE_ORDER=($APP->get('RES.0.constructr_pages_order')+1);

                $APP->set('CREATE_PAGE3',$APP->get('DBCON')->exec(
                    ['INSERT INTO constructr_pages SET constructr_pages_css=:PAGE_CSS,constructr_pages_css_uncompressed=:PAGE_CSS_UNCOMPRESSED,constructr_pages_js=:PAGE_JS,constructr_pages_js_uncompressed=:PAGE_JS_UNCOMPRESSED,constructr_pages_level=:PAGE_LEVEL,constructr_pages_mother=:PAGE_MOTHER,constructr_pages_order=:PAGE_ORDER,constructr_pages_datetime=:PAGE_DATETIME,constructr_pages_name=:PAGE_NAME,constructr_pages_nav_visible=:PAGE_VISIBILITY,constructr_pages_url=:PAGE_URL,constructr_pages_ext_url=:PAGE_EXT_URL,constructr_pages_template=:PAGE_TEMPLATE,constructr_pages_title=:PAGE_TITLE,constructr_pages_description=:PAGE_DESCRIPTION,constructr_pages_keywords=:PAGE_KEYWORDS,constructr_pages_active=:PAGE_ACTIVE;'],
					[[
                        ':PAGE_LEVEL'=>$PAGE_LEVEL,
                        ':PAGE_MOTHER'=>$PAGE_MOTHER,
                        ':PAGE_ORDER'=>$PAGE_ORDER,
                        ':PAGE_NAME'=>$PAGE_NAME,
                        ':PAGE_URL'=>$PAGE_URL,
                        ':PAGE_EXT_URL'=>$PAGE_EXT_URL,
                        ':PAGE_TEMPLATE'=>$PAGE_TEMPLATE,
                        ':PAGE_CSS'=>$PAGE_CSS,
                        ':PAGE_CSS_UNCOMPRESSED'=>$PAGE_CSS_UNCOMPRESSED,
                        ':PAGE_JS'=>$PAGE_JS,
                        ':PAGE_JS_UNCOMPRESSED'=>$PAGE_JS_UNCOMPRESSED,
                        ':PAGE_TITLE'=>$PAGE_TITLE,
                        ':PAGE_DESCRIPTION'=>$PAGE_DESCRIPTION,
                        ':PAGE_KEYWORDS'=>$PAGE_KEYWORDS,
                        ':PAGE_DATETIME'=>$PAGE_DATETIME,
                        ':PAGE_VISIBILITY'=>$PAGE_VISIBILITY,
                        ':PAGE_ACTIVE'=>$PAGE_ACTIVE
					]]
                ));
            } else {
                $PAGE_LEVEL=1;
                $PAGE_MOTHER=0;
                $PAGE_ORDER=($PAGES_COUNTR+1);

                $APP->set('CREATE_PAGE1',$APP->get('DBCON')->exec(
                    ['INSERT INTO constructr_pages SET constructr_pages_css=:PAGE_CSS,constructr_pages_css_uncompressed=:PAGE_CSS_UNCOMPRESSED,constructr_pages_js=:PAGE_JS,constructr_pages_js_uncompressed=:PAGE_JS_UNCOMPRESSED,constructr_pages_level=:PAGE_LEVEL,constructr_pages_mother=:PAGE_MOTHER,constructr_pages_order=:PAGE_ORDER,constructr_pages_datetime=:PAGE_DATETIME,constructr_pages_name=:PAGE_NAME,constructr_pages_nav_visible=:PAGE_VISIBILITY,constructr_pages_url=:PAGE_URL,constructr_pages_ext_url=:PAGE_EXT_URL,constructr_pages_template=:PAGE_TEMPLATE,constructr_pages_title=:PAGE_TITLE,constructr_pages_description=:PAGE_DESCRIPTION,constructr_pages_keywords=:PAGE_KEYWORDS,constructr_pages_active=:PAGE_ACTIVE;'],
					[[

                        ':PAGE_LEVEL'=>$PAGE_LEVEL,
                        ':PAGE_MOTHER'=>$PAGE_MOTHER,
                        ':PAGE_ORDER'=>$PAGE_ORDER,
                        ':PAGE_NAME'=>$PAGE_NAME,
                        ':PAGE_URL'=>$PAGE_URL,
                        ':PAGE_EXT_URL'=>$PAGE_EXT_URL,
                        ':PAGE_TEMPLATE'=>$PAGE_TEMPLATE,
                        ':PAGE_CSS'=>$PAGE_CSS,
                        ':PAGE_CSS_UNCOMPRESSED'=>$PAGE_CSS_UNCOMPRESSED,
                        ':PAGE_JS'=>$PAGE_JS,
                        ':PAGE_JS_UNCOMPRESSED'=>$PAGE_JS_UNCOMPRESSED,
                        ':PAGE_TITLE'=>$PAGE_TITLE,
                        ':PAGE_DESCRIPTION'=>$PAGE_DESCRIPTION,
                        ':PAGE_KEYWORDS'=>$PAGE_KEYWORDS,
                        ':PAGE_DATETIME'=>$PAGE_DATETIME,
                        ':PAGE_VISIBILITY'=>$PAGE_VISIBILITY,
                        ':PAGE_ACTIVE'=>$PAGE_ACTIVE
                    ]]
                ));
            }

			parent::clean_up_cache($APP);

            $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?new=success');
        }

        public function page_management_delete_with_content($APP){
            $APP->set('MODUL_ID',33);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            $APP->set('MODUL_ID',54);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $DELETE_PAGE_ID=filter_var($APP->get('PARAMS.page_id'),FILTER_SANITIZE_NUMBER_INT);

            $APP->set('ACTIVE_MOTHER',$APP->get('DBCON')->exec(
                    ['SELECT constructr_pages_id FROM constructr_pages WHERE constructr_pages_mother=:DELETE_PAGE_ID LIMIT 1;'],
                    [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                )
            );

            $MOTHER_COUNTR=count($APP->get('ACTIVE_MOTHER'));

            $APP->set('GET_DELETE_PAGE',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_id=:DELETE_PAGE_ID LIMIT 1;'],
                    [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                )
            );

            if(count($APP->get('GET_DELETE_PAGE'))==1){
                $DELETER_PAGE_ORDER=$APP->get('GET_DELETE_PAGE.0.constructr_pages_order');
            }else{
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success');
				die();
            }

            if($DELETER_PAGE_ORDER==1){
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success-is-homepage');
				die();
            }

            if($MOTHER_COUNTR==0 && $DELETER_PAGE_ORDER!=1){
                $APP->set('DELETE_PAGE',$APP->get('DBCON')->exec(
                        ['DELETE FROM constructr_pages WHERE constructr_pages_id=:DELETE_PAGE_ID LIMIT 1;'],
                        [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                    )
                );

                $APP->set('UPDATER',$APP->get('DBCON')->exec(
                        ['UPDATE constructr_pages SET constructr_pages_order=(constructr_pages_order-1) WHERE constructr_pages_order>=:DELETER_PAGE_ORDER;'],
                        [[':DELETER_PAGE_ORDER'=>$DELETER_PAGE_ORDER]]
                    )
                );

	            $APP->get('DBCON')->exec(
                    ['DELETE FROM constructr_content WHERE constructr_content_page_id=:DELETE_PAGE_ID;'],
                    [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                );

				parent::clean_up_cache($APP);

                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=success');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success');
            }
        }

        public function page_management_delete($APP){
            $APP->set('MODUL_ID',33);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $DELETE_PAGE_ID=filter_var($APP->get('PARAMS.page_id'),FILTER_SANITIZE_NUMBER_INT);

            $APP->set('CONTENT_COUNTR',$APP->get('DBCON')->exec(
                    ['SELECT constructr_content_id FROM constructr_content WHERE constructr_content_page_id=:DELETE_PAGE_ID;'],
                    [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                )
            );

            $CONTENT_COUNTR=count($APP->get('CONTENT_COUNTR'));

            $APP->set('ACTIVE_MOTHER',$APP->get('DBCON')->exec(
                    ['SELECT constructr_pages_id FROM constructr_pages WHERE constructr_pages_mother=:DELETE_PAGE_ID LIMIT 1;'],
                    [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                )
            );

            $MOTHER_COUNTR=count($APP->get('ACTIVE_MOTHER'));

            $APP->set('GET_DELETE_PAGE',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_id=:DELETE_PAGE_ID LIMIT 1;'],
                    [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                )
            );

            if(count($APP->get('GET_DELETE_PAGE'))==1){
                $DELETER_PAGE_ORDER=$APP->get('GET_DELETE_PAGE.0.constructr_pages_order');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success');
				die();
            }

            if ($DELETER_PAGE_ORDER==1){
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success-is-homepage');
				die();
            }

            if ($CONTENT_COUNTR!=0){
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success-content-available');
				die();
            }

            if ($MOTHER_COUNTR==0 && $DELETER_PAGE_ORDER!=1 && $CONTENT_COUNTR==0){
                $APP->set('DELETE_PAGE',$APP->get('DBCON')->exec(
                        ['DELETE FROM constructr_pages WHERE constructr_pages_id=:DELETE_PAGE_ID LIMIT 1;'],
                        [[':DELETE_PAGE_ID'=>$DELETE_PAGE_ID]]
                    )
                );

                $APP->set('UPDATER',$APP->get('DBCON')->exec(
                        ['UPDATE constructr_pages SET constructr_pages_order=(constructr_pages_order-1) WHERE constructr_pages_order>=:DELETER_PAGE_ORDER;'],
                        [[':DELETER_PAGE_ORDER'=>$DELETER_PAGE_ORDER]]
                    )
                );

				parent::clean_up_cache($APP);

                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=success');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?delete=no-success');
            }
        }

		public function page_management_change_visibility($APP){
            $APP->set('MODUL_ID',32);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

			$WHAT=filter_var($APP->get('PARAMS.what'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$PAGE_ID=filter_var($APP->get('PARAMS.page_id'),FILTER_SANITIZE_NUMBER_INT);
			$PAGE_ORDER=filter_var($APP->get('PARAMS.page_order'),FILTER_SANITIZE_NUMBER_INT);

			if($PAGE_ORDER == 1){
				$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=no-success-visibility-homepage');
			}

			if($WHAT!='' && $PAGE_ID!='' && $PAGE_ORDER!=''){
				if($WHAT=='on'){
	                $APP->set('UPDATER',$APP->get('DBCON')->exec(
	                        ['UPDATE constructr_pages SET constructr_pages_nav_visible=1 WHERE constructr_pages_id=:PAGE_ID LIMIT 1;'],
	                        [[':PAGE_ID'=>$PAGE_ID]]
	                    )
	                );

	                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=success');
				} else {
	                $APP->set('UPDATER',$APP->get('DBCON')->exec(
	                        ['UPDATE constructr_pages SET constructr_pages_nav_visible=0 WHERE constructr_pages_id=:PAGE_ID LIMIT 1;'],
	                        [[':PAGE_ID'=>$PAGE_ID]]
	                    )
	                );

					parent::clean_up_cache($APP);

	                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=success');
				}
			} else {
				$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?edit=no-success');
			}
		}

        public function page_management_move_up($APP){
            $APP->set('MODUL_ID',34);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $MOVE_PAGE_ID=filter_var($APP->get('PARAMS.page_id'),FILTER_SANITIZE_NUMBER_INT);

            $APP->set('MOVE_PAGE',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_id=:DELETE_PAGE_ID LIMIT 1;'],
                    [[':DELETE_PAGE_ID'=>$MOVE_PAGE_ID]]
                )
            );

            if (count($APP->get('MOVE_PAGE'))==1){
                $MOVE_PAGE_ORDER=$APP->get('MOVE_PAGE.0.constructr_pages_order');
                $MOVE_PAGE_LEVEL=$APP->get('MOVE_PAGE.0.constructr_pages_level');
                $MOVE_PAGE_MOTHER=$APP->get('MOVE_PAGE.0.constructr_pages_mother');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success');
            }

			if($MOVE_PAGE_ORDER == 1){
				$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success-homepage');
			}

            $TARGET_PAGE_ORDER=($MOVE_PAGE_ORDER - 1);

            $APP->set('TARGET_PAGE',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_order=:TARGET_PAGE_ORDER LIMIT 1;'],
                    [[':TARGET_PAGE_ORDER'=>$TARGET_PAGE_ORDER]]
                )
            );

            if (count($APP->get('TARGET_PAGE'))==1){
                $TARGET_PAGE_ID=$APP->get('TARGET_PAGE.0.constructr_pages_id');
                $TARGET_PAGE_LEVEL=$APP->get('TARGET_PAGE.0.constructr_pages_level');
                $TARGET_PAGE_MOTHER=$APP->get('TARGET_PAGE.0.constructr_pages_mother');
                $TARGET_PAGE_ORDER=$APP->get('TARGET_PAGE.0.constructr_pages_order');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success');
            }

			if($TARGET_PAGE_ORDER == 1){
				$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success-homepage');
			}

            $APP->set('UPDATR_TARGET_STEP_ONE',$APP->get('DBCON')->exec(
                ['UPDATE constructr_pages SET constructr_pages_id=:TMP_PAGE_ID,constructr_pages_order=:TMP_PAGE_ORDER,constructr_pages_level=:TMP_PAGE_LEVEL,constructr_pages_mother=:TMP_PAGE_MOTHER,constructr_pages_temp_marker=:TMP_PAGE_MARKER WHERE constructr_pages_id=:TARGET_PAGE_ID LIMIT 1;'],
                [[
                    ':TMP_PAGE_ID'=>0,
                    ':TMP_PAGE_ORDER'=>666,
                    ':TMP_PAGE_LEVEL'=>666,
                    ':TMP_PAGE_MOTHER'=>666,
                    ':TMP_PAGE_MARKER'=>666,
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID
                ]]
            ));

            $APP->set('UPDATR_MOVE',$APP->get('DBCON')->exec(
                ['UPDATE constructr_pages SET constructr_pages_id=:TARGET_PAGE_ID,constructr_pages_order=:TARGET_PAGE_ORDER,constructr_pages_level=:TARGET_PAGE_LEVEL,constructr_pages_mother=:TARGET_PAGE_MOTHER WHERE constructr_pages_id=:MOVE_PAGE_ID LIMIT 1;'],
                [[
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID,
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID,
                    ':TARGET_PAGE_ORDER'=>$TARGET_PAGE_ORDER,
                    ':TARGET_PAGE_LEVEL'=>$TARGET_PAGE_LEVEL,
                    ':TARGET_PAGE_MOTHER'=>$TARGET_PAGE_MOTHER
                ]]
            ));

            $APP->set('UPDATR_TARGET',$APP->get('DBCON')->exec(
                ['UPDATE constructr_pages SET constructr_pages_id=:MOVE_PAGE_ID,constructr_pages_order=:MOVE_PAGE_ORDER,constructr_pages_level=:MOVE_PAGE_LEVEL,constructr_pages_mother=:MOVE_PAGE_MOTHER,constructr_pages_temp_marker=:TMP_MARKER WHERE constructr_pages_id=:TARGET_PAGE_ID LIMIT 1;'],
                [[
                    ':TMP_MARKER'=>0,
                    ':TARGET_PAGE_ID'=>0,
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID,
                    ':MOVE_PAGE_ORDER'=>$MOVE_PAGE_ORDER,
                    ':MOVE_PAGE_LEVEL'=>$MOVE_PAGE_LEVEL,
                    ':MOVE_PAGE_MOTHER'=>$MOVE_PAGE_MOTHER
                ]]
            ));

            $APP->set('CONTENT_UPDATR1',$APP->get('DBCON')->exec(
                ['UPDATE constructr_content SET constructr_content_page_id=:TMP_MARKER WHERE constructr_content_page_id=:TARGET_PAGE_ID;'],
				[[
                    ':TMP_MARKER'=>9999,
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID
                ]]
            ));

            $APP->set('CONTENT_UPDATR2',$APP->get('DBCON')->exec(
                ['UPDATE constructr_content SET constructr_content_page_id=:TARGET_PAGE_ID WHERE constructr_content_page_id=:MOVE_PAGE_ID;'],
				[[
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID,
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID
				]]
            ));

            $APP->set('CONTENT_UPDATR3',$APP->get('DBCON')->exec(
                ['UPDATE constructr_content SET constructr_content_page_id=:MOVE_PAGE_ID WHERE constructr_content_page_id=:TMP_MARKER;'],
                [[
                    ':TMP_MARKER'=>9999,
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID
				]]
            ));

			parent::clean_up_cache($APP);

            $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=success');
        }

        public function page_management_move_down($APP){
            $APP->set('MODUL_ID',34);
            $USER_RIGHTS=parent::checkUserModulRights($APP->get('MODUL_ID'),$APP->get('LOGIN_USER_RIGHTS'));

            if ($USER_RIGHTS==false){
                $APP->get('CONSTRUCTR_LOG')->write('User '.$APP->get('SESSION.username').' missing USER-RIGHTS for modul '.$APP->get('MODUL_ID'));
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/no-rights');
            }

            $MOVE_PAGE_ID=filter_var($APP->get('PARAMS.page_id'),FILTER_SANITIZE_NUMBER_INT);

            $APP->set('MOVE_PAGE',$APP->get('DBCON')->exec(
                    ['SELECT * FROM constructr_pages WHERE constructr_pages_id=:DELETE_PAGE_ID LIMIT 1;'],
                    [[':DELETE_PAGE_ID'=>$MOVE_PAGE_ID]]
                )
            );

            if (count($APP->get('MOVE_PAGE'))==1){
                $MOVE_PAGE_ORDER=$APP->get('MOVE_PAGE.0.constructr_pages_order');
                $MOVE_PAGE_LEVEL=$APP->get('MOVE_PAGE.0.constructr_pages_level');
                $MOVE_PAGE_MOTHER=$APP->get('MOVE_PAGE.0.constructr_pages_mother');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success');
            }

			if($MOVE_PAGE_ORDER == 1){
				$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success-homepage');
			}

            $TARGET_PAGE_ORDER=($MOVE_PAGE_ORDER + 1);

            $APP->set('TARGET_PAGE',$APP->get('DBCON')->exec(
                ['SELECT * FROM constructr_pages WHERE constructr_pages_order=:TARGET_PAGE_ORDER LIMIT 1;'],
                [[':TARGET_PAGE_ORDER'=>$TARGET_PAGE_ORDER]]
            ));

            if (count($APP->get('TARGET_PAGE'))==1){
                $TARGET_PAGE_ID=$APP->get('TARGET_PAGE.0.constructr_pages_id');
                $TARGET_PAGE_LEVEL=$APP->get('TARGET_PAGE.0.constructr_pages_level');
                $TARGET_PAGE_MOTHER=$APP->get('TARGET_PAGE.0.constructr_pages_mother');
                $TARGET_PAGE_ORDER=$APP->get('TARGET_PAGE.0.constructr_pages_order');
            } else {
                $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success');
            }

			if($TARGET_PAGE_ORDER == 1){
				$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=no-success-homepage');
			}

            $APP->set('UPDATR_TARGET_STEP_ONE',$APP->get('DBCON')->exec(
                ['UPDATE constructr_pages SET constructr_pages_id=:TMP_PAGE_ID,constructr_pages_order=:TMP_PAGE_ORDER,constructr_pages_level=:TMP_PAGE_LEVEL,constructr_pages_mother=:TMP_PAGE_MOTHER,constructr_pages_temp_marker=:TMP_PAGE_MARKER WHERE constructr_pages_id=:TARGET_PAGE_ID LIMIT 1;'],
				[[
                    ':TMP_PAGE_ID'=>0,
                    ':TMP_PAGE_ORDER'=>666,
                    ':TMP_PAGE_LEVEL'=>666,
                    ':TMP_PAGE_MOTHER'=>666,
                    ':TMP_PAGE_MARKER'=>666,
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID
				]]
            ));

            $APP->set('UPDATR_MOVE',$APP->get('DBCON')->exec(
                ['UPDATE constructr_pages SET constructr_pages_id=:TARGET_PAGE_ID,constructr_pages_order=:TARGET_PAGE_ORDER,constructr_pages_level=:TARGET_PAGE_LEVEL,constructr_pages_mother=:TARGET_PAGE_MOTHER WHERE constructr_pages_id=:MOVE_PAGE_ID LIMIT 1;'],
                [[
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID,
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID,
                    ':TARGET_PAGE_ORDER'=>$TARGET_PAGE_ORDER,
                    ':TARGET_PAGE_LEVEL'=>$TARGET_PAGE_LEVEL,
                    ':TARGET_PAGE_MOTHER'=>$TARGET_PAGE_MOTHER
                ]]
            ));

            $APP->set('UPDATR_TARGET',$APP->get('DBCON')->exec(
                ['UPDATE constructr_pages SET constructr_pages_id=:MOVE_PAGE_ID,constructr_pages_order=:MOVE_PAGE_ORDER,constructr_pages_level=:MOVE_PAGE_LEVEL,constructr_pages_mother=:MOVE_PAGE_MOTHER,constructr_pages_temp_marker=:TMP_MARKER WHERE constructr_pages_id=:TARGET_PAGE_ID LIMIT 1;'],
				[[
                    ':TMP_MARKER'=>0,
                    ':TARGET_PAGE_ID'=>0,
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID,
                    ':MOVE_PAGE_ORDER'=>$MOVE_PAGE_ORDER,
                    ':MOVE_PAGE_LEVEL'=>$MOVE_PAGE_LEVEL,
                    ':MOVE_PAGE_MOTHER'=>$MOVE_PAGE_MOTHER
                ]]
            ));

            $APP->set('CONTENT_UPDATR1',$APP->get('DBCON')->exec(
                ['UPDATE constructr_content SET constructr_content_page_id=:TMP_MARKER WHERE constructr_content_page_id=:TARGET_PAGE_ID;'],
				[[
	                ':TMP_MARKER'=>9999,
	                ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID
                ]]
            ));

            $APP->set('CONTENT_UPDATR2',$APP->get('DBCON')->exec(
                ['UPDATE constructr_content SET constructr_content_page_id=:TARGET_PAGE_ID WHERE constructr_content_page_id=:MOVE_PAGE_ID;'],
                [[
                    ':TARGET_PAGE_ID'=>$TARGET_PAGE_ID,
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID
                ]]
            ));

            $APP->set('CONTENT_UPDATR3',$APP->get('DBCON')->exec(
                ['UPDATE constructr_content SET constructr_content_page_id=:MOVE_PAGE_ID WHERE constructr_content_page_id=:TMP_MARKER;'],
                [[
                    ':TMP_MARKER'=>9999,
                    ':MOVE_PAGE_ID'=>$MOVE_PAGE_ID
                ]]
            ));

			parent::clean_up_cache($APP);

            $APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?move=success');
        }

		public function mrCleaner($APP){
			parent::clean_up_cache($APP);
			$APP->reroute($APP->get('CONSTRUCTR_BASE_URL').'/constructr/pagemanagement?mrcleaner=success');
		}

        public function admin_404($APP){
            echo Template::instance()->render('protected/TEMPLATES/constructr_404.html','text/html');
        }

        public function admin_error($APP){
            echo Template::instance()->render('protected/TEMPLATES/constructr_error.html','text/html');
        }

		public function page_management_make_slug($APP){
			$MESSY_URL=$APP->get('POST.messy_url');
			echo self::cleanUrl($MESSY_URL);
		}

        public function cleanUrl($str){
            $str=str_replace('À','-',$str);
            $str=str_replace('Á','-',$str);
            $str=str_replace('Â','-',$str);
            $str=str_replace('Ã','-',$str);
            $str=str_replace('Ä','-',$str);
            $str=str_replace('Å','-',$str);
            $str=str_replace('Æ','-',$str);
            $str=str_replace('Ç','-',$str);
            $str=str_replace('È','-',$str);
            $str=str_replace('É','-',$str);
            $str=str_replace('Ê','-',$str);
            $str=str_replace('Ë','-',$str);
            $str=str_replace('Ì','-',$str);
            $str=str_replace('Í','-',$str);
            $str=str_replace('Î','-',$str);
            $str=str_replace('Ï','-',$str);
            $str=str_replace('Ð','-',$str);
            $str=str_replace('Ñ','-',$str);
            $str=str_replace('Ò','-',$str);
            $str=str_replace('Ó','-',$str);
            $str=str_replace('Ô','-',$str);
            $str=str_replace('Õ','-',$str);
            $str=str_replace('Ö','-',$str);
            $str=str_replace('×','-',$str);
            $str=str_replace('Ø','-',$str);
            $str=str_replace('Ù','-',$str);
            $str=str_replace('Ú','-',$str);
            $str=str_replace('Û','-',$str);
            $str=str_replace('Ü','-',$str);
            $str=str_replace('Ý','-',$str);
            $str=str_replace('Þ','-',$str);
            $str=str_replace('ß','-',$str);
            $str=str_replace('à','-',$str);
            $str=str_replace('á','-',$str);
            $str=str_replace('â','-',$str);
            $str=str_replace('ã','-',$str);
            $str=str_replace('ä','-',$str);
            $str=str_replace('å','-',$str);
            $str=str_replace('æ','-',$str);
            $str=str_replace('ç','-',$str);
            $str=str_replace('è','-',$str);
            $str=str_replace('é','-',$str);
            $str=str_replace('ê','-',$str);
            $str=str_replace('ë','-',$str);
            $str=str_replace('ì','-',$str);
            $str=str_replace('í','-',$str);
            $str=str_replace('î','-',$str);
            $str=str_replace('ï','-',$str);
            $str=str_replace('ð','-',$str);
            $str=str_replace('ñ','-',$str);
            $str=str_replace('ò','-',$str);
            $str=str_replace('ó','-',$str);
            $str=str_replace('ô','-',$str);
            $str=str_replace('õ','-',$str);
            $str=str_replace('ö','-',$str);
            $str=str_replace('÷','-',$str);
            $str=str_replace('ø','-',$str);
            $str=str_replace('ù','-',$str);
            $str=str_replace('ú','-',$str);
            $str=str_replace('û','-',$str);
            $str=str_replace('ü','-',$str);
            $str=str_replace('ý','-',$str);
            $str=str_replace('þ','-',$str);
            $str=str_replace('ÿ','-',$str);
			$str=str_replace(' ','_',$str);
            return $str;
        }

	    public function gffd($dir){
	        $files=[];
	        if ($handle=opendir($dir)){
	            while (false!==($file=readdir($handle))){
	                if ($file!="." && $file!=".."){
	                    if (is_dir($dir.'/'.$file)){
	                        $dir2=$dir.'/'.$file;
	                        $files[]=self::gffd($dir2);
	                    } else {
	                        $files[]=$dir.'/'.$file;
	                    }
	                }
	            }
	            closedir($handle);
	        }

	        return self::flatten_array($files);
	    }

	    public function flatten_array($array){
	        $flat_array=[];
	        $size=sizeof($array);
	        $keys=array_keys($array);

	        for ($x=0; $x < $size; $x++){
	            $element=$array[$keys[$x]]; if(is_array($element)){
	    			$results=self::flatten_array($element);
					$sr=sizeof($results);
	    			$sk=array_keys($results);

	    			for ($y=0; $y < $sr; $y++){
	        			$flat_array[$sk[$y]]=$results[$sk[$y]];
	    			}

				} else {
	    			$flat_array[$keys[$x]]=$element;
				}
	        }

	        return $flat_array;
	    }
    }
