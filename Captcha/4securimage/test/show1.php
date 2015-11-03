<?php
session_start();
?>
<form action="#" method="post">
<p>
    <img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="./securimage_show.php?securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left">
    &nbsp;
    <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = './securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="./images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br />
    <strong>Enter Code*:</strong><br />
    <input type="text" name="securimage_code_value" id="securimage_code_value" size="12" maxlength="16" />
    <input type="submit" name="a">
</p>
    </form>
<?php


//打印全部session;
print_r($_SESSION);
if(isset($_POST)) {
    print_r($_POST);
    if ($_POST['securimage_code_value'] == $_SESSION['securimage_code_value']['default']) {
        echo "right";
    } else {
        echo $_POST['securimage_code_value'];
        print_r($_SESSION['securimage_code_value']);
    }
}
?>


