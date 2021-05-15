Hello <?= isset($username) ? $username : 'User'; ?> <br>
To reset your forgotten password please click the link below: <br>
<a
        href=<?php if (isset($id) & isset($token)) echo "/password/reset/$id/$token" ?>
>
    Reset Password
</a>
<br>

If you are not requested this email, just ignore it and don't click any link<br>
Thank you
