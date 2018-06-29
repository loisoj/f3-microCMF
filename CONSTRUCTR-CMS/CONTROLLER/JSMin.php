<?php
/**
 * JSMin.php - modified PHP implementation of Douglas Crockford's JSMin.
 *
 * <code>
 * $minifiedJs = JSMin::minify($js);
 * </code>
 *
 * This is a modified port of jsmin.c. Improvements:
 *
 * Does not choke on some regexp literals containing quote characters. E.g. /'/
 *
 * Spaces are preserved after some add/sub operators, so they are not mistakenly
 * converted to post-inc/dec. E.g. a + ++b -> a+ ++b
 *
 * Preserves multi-line comments that begin with /*!
 *
 * PHP 5 or higher is required.
 *
 * Permission is hereby granted to use this version of the library under the
 * same terms as jsmin.c, which has the following license:
 *
 * --
 * Copyright (c) 2002 Douglas Crockford  (www.crockford.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * The Software shall be used for Good, not Evil.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * --
 *
 * @package JSMin
 * @author Ryan Grove <ryan@wonko.com> (PHP port)
 * @author Steve Clay <steve@mrclay.org> (modifications + cleanup)
 * @author Andrea Giammarchi <http://www.3site.eu> (spaceBeforeRegExp)
 * @copyright 2002 Douglas Crockford <douglas@crockford.com> (jsmin.c)
 * @copyright 2008 Ryan Grove <ryan@wonko.com> (PHP port)
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/jsmin-php/
 */
class JSMin{const ORD_LF=10;const ORD_SPACE=32;const ACTION_KEEP_A=1;const ACTION_DELETE_A=2;const ACTION_DELETE_A_B=3; protected $a="\n"; protected $b=''; protected $input=''; protected $inputIndex=0; protected $inputLength=0; protected $lookAhead=null; protected $output=''; protected $lastByteOut=''; protected $keptComment=''; public static function minify($js){$jsmin=new JSMin($js);return $jsmin->min();} public function __construct($input){$this->input=$input;} public function min(){if($this->output!==''){return $this->output;}$mbIntEnc=null;if(function_exists('mb_strlen')&&((int)ini_get('mbstring.func_overload')&2)){$mbIntEnc=mb_internal_encoding();mb_internal_encoding('8bit');}$this->input=str_replace("\r\n","\n",$this->input);$this->inputLength=strlen($this->input);$this->action(self::ACTION_DELETE_A_B);while($this->a!==null){$command=self::ACTION_KEEP_A;if($this->a===' '){if(($this->lastByteOut==='+'||$this->lastByteOut==='-')&&($this->b===$this->lastByteOut)){}elseif(!$this->isAlphaNum($this->b)){$command=self::ACTION_DELETE_A;}}elseif($this->a==="\n"){if($this->b===' '){$command=self::ACTION_DELETE_A_B;}elseif($this->b===null||(false===strpos('{[(+-!~',$this->b)&&!$this->isAlphaNum($this->b))){$command=self::ACTION_DELETE_A;}}elseif(!$this->isAlphaNum($this->a)){if($this->b===' '||($this->b==="\n"&&(false===strpos('}])+-"\'',$this->a)))){$command=self::ACTION_DELETE_A_B;}}$this->action($command);}$this->output=trim($this->output);if($mbIntEnc!==null){mb_internal_encoding($mbIntEnc);}return $this->output;} protected function action($command){if($command===self::ACTION_DELETE_A_B&&$this->b===' '&&($this->a==='+'||$this->a==='-')){if($this->input[$this->inputIndex]===$this->a){$command=self::ACTION_KEEP_A;}}switch($command){case self::ACTION_KEEP_A:$this->output.=$this->a;if($this->keptComment){$this->output=rtrim($this->output,"\n");$this->output.=$this->keptComment;$this->keptComment='';}$this->lastByteOut=$this->a;case self::ACTION_DELETE_A:$this->a=$this->b;if($this->a==="'"||$this->a==='"'){$str=$this->a;for(;;){$this->output.=$this->a;$this->lastByteOut=$this->a;$this->a=$this->get();if($this->a===$this->b){break;}if($this->isEOF($this->a)){$byte=$this->inputIndex-1; throw new JSMin_UnterminatedStringException("JSMin: Unterminated String at byte {$byte}: {$str}");}$str.=$this->a;if($this->a==='\\'){$this->output.=$this->a;$this->lastByteOut=$this->a;$this->a=$this->get();$str.=$this->a;}}}case self::ACTION_DELETE_A_B:$this->b=$this->next();if($this->b==='/'&&$this->isRegexpLiteral()){$this->output.=$this->a.$this->b;$pattern='/';for(;;){$this->a=$this->get();$pattern.=$this->a;if($this->a==='['){for(;;){$this->output.=$this->a;$this->a=$this->get();$pattern.=$this->a;if($this->a===']'){break;}if($this->a==='\\'){$this->output.=$this->a;$this->a=$this->get();$pattern.=$this->a;}if($this->isEOF($this->a)){ throw new JSMin_UnterminatedRegExpException("JSMin: Unterminated set in RegExp at byte ".$this->inputIndex.": {$pattern}");}}}if($this->a==='/'){break;}elseif($this->a==='\\'){$this->output.=$this->a;$this->a=$this->get();$pattern.=$this->a;}elseif($this->isEOF($this->a)){$byte=$this->inputIndex-1; throw new JSMin_UnterminatedRegExpException("JSMin: Unterminated RegExp at byte {$byte}: {$pattern}");}$this->output.=$this->a;$this->lastByteOut=$this->a;}$this->b=$this->next();}}} protected function isRegexpLiteral(){if(false!==strpos("(,=:[!&|?+-~*{;",$this->a)){return true;}$recentOutput=substr($this->output,-10);foreach(array('return','typeof') as $keyword){if($this->a!==substr($keyword,-1)){continue;}if(preg_match("~(^|[\\s\\S])".substr($keyword,0,-1)."$~",$recentOutput,$m)){if($m[1]===''||!$this->isAlphaNum($m[1])){return true;}}}if($this->a===' '||$this->a==="\n"){if(preg_match('~(^|[\\s\\S])(?:case|else|in|return|typeof)$~',$recentOutput,$m)){if($m[1]===''||!$this->isAlphaNum($m[1])){return true;}}}return false;} protected function get(){$c=$this->lookAhead;$this->lookAhead=null;if($c===null){if($this->inputIndex<$this->inputLength){$c=$this->input[$this->inputIndex];$this->inputIndex+=1;}else {$c=null;}}if(ord($c)>=self::ORD_SPACE||$c==="\n"||$c===null){return $c;}if($c==="\r"){return "\n";}return ' ';} protected function isEOF($a){return ord($a)<=self::ORD_LF;} protected function peek(){$this->lookAhead=$this->get();return $this->lookAhead;} protected function isAlphaNum($c){return (preg_match('/^[a-z0-9A-Z_\\$\\\\]$/',$c)||ord($c)>126);} protected function consumeSingleLineComment(){$comment='';while(true){$get=$this->get();$comment.=$get;if(ord($get)<=self::ORD_LF){if(preg_match('/^\\/@(?:cc_on|if|elif|else|end)\\b/',$comment)){$this->keptComment.="/{$comment}";}return;}}} protected function consumeMultipleLineComment(){$this->get();$comment='';for(;;){$get=$this->get();if($get==='*'){if($this->peek()==='/'){$this->get();if(0===strpos($comment,'!')){if(!$this->keptComment){$this->keptComment="\n";}$this->keptComment.="/*!".substr($comment,1)."*/\n";}else if(preg_match('/^@(?:cc_on|if|elif|else|end)\\b/',$comment)){$this->keptComment.="/*{$comment}*/";}return;}}elseif($get===null){ throw new JSMin_UnterminatedCommentException("JSMin: Unterminated comment at byte {$this->inputIndex}: /*{$comment}");}$comment.=$get;}} protected function next(){$get=$this->get();if($get==='/'){switch($this->peek()){case '/':$this->consumeSingleLineComment();$get="\n";break;case '*':$this->consumeMultipleLineComment();$get=' ';break;}}return $get;}}class JSMin_UnterminatedStringException extends Exception{}class JSMin_UnterminatedCommentException extends Exception{}class JSMin_UnterminatedRegExpException extends Exception{}