Hello <?= isset($username) ? $username : 'User'; ?> <br>
To verify your account please click the link below: <br>
<a
	href=<?php if (isset($id) & isset($token)) echo "/verify/email/$id/$token"?>
>
	Verify Account
</a>
<br>

If you are not registered with this email, just ignore it and don't click any link<br>
Thank you
