<?php include "../include/top.php"; ?>

<style type="text/css">
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    html,
    div,
    p,
    button,
    input {
        font-family: 'SCDream2';
        font-weight: bold;
    }

    a {
        text-decoration: none;
    }

    a:link {
        color: white;
    }

    a:visited {
        color: white;
    }

    a:hover {
        color: white;
    }

    a:active {
        color: white;
    }
    
    .go_login {
        background-color: #f5bf2e;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-family: 'SCDream2';
        font-size: 18px;
        font-weight: bold;
        font-style: normal;
        height: 42px;
        line-height: 42px;
        width: 154px;
        text-decoration: none;
        text-align: center;
    }

	.go_event {
        background-color: #6ab5dc;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 1px solid #6ab5dc;
        display: inline-block;
        color: #ffffff;
        font-family: 'SCDream2';
        font-size: 18px;
        font-weight: bold;
        font-style: normal;
        height: 42px;
        line-height: 42px;
        width: 90%;
        text-decoration: none;
        text-align: center;
		margin-top: 20px;
    }

    .go_login:hover {

        background-color: #f5bf2e;
    }

    .go_login:active {
        position: relative;
        top: 1px;
    }

    .join_id {
        text-align: center;
        font-size: 20px;
    }
</style>

<center>
    <table widht="80%">
        <tr>
            <td height="30px"></td>
        </tr>
        <tr>
            <td height="30px" align="center"><img src="<?= $image_directory ?>/logo_02.png" width="40%"></td>
        </tr>
        <tr>
            <td height="30px"></td>
        </tr>
        <tr>
            <td align="center" style="font-size:18px;">
                <table class="join_id">
                    <tr>
                        <td>
                            <?= $_SESSION["gobeauty_login_id_t"] ?>님
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="30px"></td>
        </tr>
        <tr>
            <td align="center" style="font-size:20px;font-weight:bold;">
                반짝 회원가입을 축하드립니다.
            </td>
        </tr>
        <tr>
            <td height="30px"></td>
        </tr>
        <tr>
            <td align="center">
                <a href="<?= $login_directory ?>/" class="go_login">로그인하기</a>
            </td>
        </tr>
		<!--
        <tr>
            <td align="center">
                <a href="https://forms.gle/pMQLtCNGg1e4zXpb9" target="_blank" class="go_event">반짝 Holiday 이벤트 참여하기</a>
            </td>
        </tr>
		-->
    </table>
</center>

<?php include "../include/bottom.php"; ?>