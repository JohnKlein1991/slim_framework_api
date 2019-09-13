<h2>Slim framework API</h2>
<p>Simple api for manage users, created with Slim Framework</p>
<p>The example of this api you can see here: <a href="http://andrew-markhai.ru">http://andrew-markhai.ru</a></p>
<p>All routes you can find in routes/api.php:</p>
<ul>
    <li>/users/list (GET) - get the list of all users</li>
    <li>/users/get-user (POST) - get user data by id, parameters:id - required</li>
    <li>/users/remove (POST) - remove user by id, parameters:id - required</li>
    <li>/users/create (POST) - add new user, parameters: username, password, name - required, email - optional </li>
    <li>/users/update (POST) - update user data by id, parameters:id - required, username, password, name, email - optional </li>
</ul>
