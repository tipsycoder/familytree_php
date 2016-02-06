<?php
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/15/15
 * Time: 11:38 PM
 */

header("Content-Type: text/html");

function getSignUpHtml() {
    $time = strtotime("-1 month", time());
    $date = date("Y-m-d", $time);

    $html = "<div class=\"form\"><h2>SIGN ME UP</h2><form action='php/user/prc_signup.php' method='POST' name='signUpForm' id='signUpForm'>" .
        "<input type='email' id='uEmail' name='uEmail' placeholder='Enter Email Here' required />" .
        "<input type='password' id='uPass' name='uPass' placeholder='Enter Password Here' minlength='6' required />" .
        "<input type='password' id='uRPass' name='uRPass' placeholder='Enter Password Again' minlength='6' required />" .
        "<input type='text' id='uFName' name='uFName' placeholder='Enter First Name Here' required />" .
        "<input type='text' id='uLName' name='uLName' placeholder='Enter Last Name Here' required />" .
        "<div><div class='wrapper'><input type='text' id='uMNames' name='uMNames' placeholder='Enter Middle Name(s)' /><div class='tooltips'>Separate Each Aliases With ';'</div></div>" .
        "<div><div class='wrapper'><input type='text' id='uAliases' name='uAliases' placeholder='Enter Aliases' /><div class='tooltips'>Separate Each Aliases With ';'</div></div>" .
        getNationalitySelect() .
        "<input type='date' min='1916-01-01' max=$date id='uDOB' name='uDOB' value=\"$date\"/>" ;

    $html .= '<img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" style="padding-bottom: 10px; display: block"/>';
    /*$html .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
			    <param name="allowScriptAccess" value="sameDomain" />
			    <param name="allowFullScreen" value="false" />
			    <param name="movie" value="securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" />
			    <param name="quality" value="high" />

			    <param name="bgcolor" value="#ffffff" />
			    <embed src="securimage/securimage_play.swf?audio=securimage/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#ffffff" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			  </object>';*/

    $html .= '<a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?sid=\' + Math.random(); return false">[Different Image]</a>';//<img src="securimage/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="bottom" /></a>';
    $html .= "<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" placeholder='Enter Captcha Code'/>";



    $html .= "<button type='submit' id='uSbm' name='uSbm' value='lol'>REGISTER</button>" .
        "</form></div>";

    return $html;
}

    function getNationalitySelect() {
        $htmlSelect = "<div class=\"select-style\"><select name='uNation' required>
            <option value='' disabled selected>Select Nationality</option>
            <option value='Jamaica'>Jamaica</option>
            <option value='Japan'>Japan</option>
            <option value='USA'>USA</option>
            </select></div>";

        return $htmlSelect;
    }


