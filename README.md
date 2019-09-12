<h2>Slim framework API</h2>
<p>Simple api for manage users, created with Slim Framework</p>
<p>All routes you can find in routes/api.php:</p>
<ul>
    <li>/users/list (GET) - get the list of all users</li>
    <li>/users/get-user/{id} (GET) - get user data by id</li>
    <li>/users/remove/{id} (DELETE) - remove user by id</li>
    <li>/users/create (POST) - add new user, parameters: username, password, name - required, email - optional </li>
    <li>/users/update (POST) - update user data by id, parameters:id - required, username, password, name, email - optional </li>
</ul>
