<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

$location = $_SERVER['SERVER_NAME'] . WEBROOT;
$first = "<script language=JavaScript>function Try(password) {   if (password ==\"";
$last = <<<EOT
") {alert("This one was easy, you will receive a point ;)");
    window.location = "https://$location/check.php?t=$token";
    return false;}else {alert("To bad, please try again.");return false;}}
//  -->
</script>
EOT;
?>
<Script Language='Javascript'>
    <!--
    document.write(unescape('%3c%73%63%72%69%70%74%20%6c%61%6e%67%75%61%67%65%3d%4a%61%76%61%53%63%72%69%70%74%3e%0d%0a%76%61%72%20%6d%65%73%73%61%67%65%3d%22%57%65%20%64%6f%20%74%72%79%20%74%6f%20%6d%61%6b%65%20%69%74%20%68%61%72%64%65%72%2e%20%3a%29%21%22%3b%66%75%6e%63%74%69%6f%6e%20%63%6c%69%63%6b%49%45%34%28%29%7b%69%66%20%28%65%76%65%6e%74%2e%62%75%74%74%6f%6e%3d%3d%32%29%7b%61%6c%65%72%74%28%6d%65%73%73%61%67%65%29%3b%72%65%74%75%72%6e%20%66%61%6c%73%65%3b%7d%7d%66%75%6e%63%74%69%6f%6e%20%63%6c%69%63%6b%4e%53%34%28%65%29%7b%69%66%20%28%64%6f%63%75%6d%65%6e%74%2e%6c%61%79%65%72%73%7c%7c%64%6f%63%75%6d%65%6e%74%2e%67%65%74%45%6c%65%6d%65%6e%74%42%79%49%64%26%26%21%64%6f%63%75%6d%65%6e%74%2e%61%6c%6c%29%7b%69%66%20%28%65%2e%77%68%69%63%68%3d%3d%32%7c%7c%65%2e%77%68%69%63%68%3d%3d%33%29%7b%61%6c%65%72%74%28%6d%65%73%73%61%67%65%29%3b%72%65%74%75%72%6e%20%66%61%6c%73%65%3b%7d%7d%7d%69%66%20%28%64%6f%63%75%6d%65%6e%74%2e%6c%61%79%65%72%73%29%7b%64%6f%63%75%6d%65%6e%74%2e%63%61%70%74%75%72%65%45%76%65%6e%74%73%28%45%76%65%6e%74%2e%4d%4f%55%53%45%44%4f%57%4e%29%3b%64%6f%63%75%6d%65%6e%74%2e%6f%6e%6d%6f%75%73%65%64%6f%77%6e%3d%63%6c%69%63%6b%4e%53%34%3b%7d%65%6c%73%65%20%69%66%20%28%64%6f%63%75%6d%65%6e%74%2e%61%6c%6c%26%26%21%64%6f%63%75%6d%65%6e%74%2e%67%65%74%45%6c%65%6d%65%6e%74%42%79%49%64%29%7b%64%6f%63%75%6d%65%6e%74%2e%6f%6e%6d%6f%75%73%65%64%6f%77%6e%3d%63%6c%69%63%6b%49%45%34%3b%7d%64%6f%63%75%6d%65%6e%74%2e%6f%6e%63%6f%6e%74%65%78%74%6d%65%6e%75%3d%6e%65%77%20%46%75%6e%63%74%69%6f%6e%28%22%61%6c%65%72%74%28%6d%65%73%73%61%67%65%29%3b%72%65%74%75%72%6e%20%66%61%6c%73%65%22%29%0d%0a%2f%2f%20%2d%2d%3e%0d%0a%3c%2f%73%63%72%69%70%74%3e'));
    //-->
</Script>

<Script Language='Javascript'>

    <!--
    document.write(unescape("<?php echo util::ascii2hex($first . $pwd ."". $last) ?>"));
    
    //-->
</Script>
Try a password and continue:<br /><br /><br />
<form autocomplete="off" action=""  onsubmit="return Try(password.value)">
    <div>
        <input type="text" name="password" size="20" />&nbsp;&nbsp;<input type="submit" class="button" value="Try this password"/>
    </div>
</form>

<?php $challenge->stopChallenge(); ?>