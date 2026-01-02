OAuth setup notes

This project includes a simple OAuth scaffolding for Google and Facebook and a placeholder for Apple Sign In.

1) Google
- Go to Google Cloud Console -> APIs & Services -> Credentials -> Create OAuth Client ID.
- Application type: Web application.
- Add an authorized redirect URI, e.g.:
  http://localhost/DIMOB/backend/auth.php?provider=google
- Copy the Client ID and Client Secret into `backend/oauth_config.php`.

2) Facebook
- Create an App in Facebook for Developers.
- Under Facebook Login -> Settings add a Valid OAuth Redirect URI, e.g.:
  http://localhost/DIMOB/backend/auth.php?provider=facebook
- Copy App ID and App Secret to `backend/oauth_config.php`.

3) Apple (Sign in with Apple)
- Sign in with Apple requires generating a JWT `client_secret` signed with the private `.p8` key from Apple.
- You must register a Service ID (client_id), create a Key (with key id) and set your team id.
- The server must generate a JWT with claims (iss, iat, exp, aud, sub) and sign it with the private key.
- This sample does NOT generate the JWT automatically. Place your private key at `backend/apple_private_key.p8` and fill `oauth_config.php` values.
- A reliable PHP library (firebase/php-jwt) helps generate the JWT; otherwise you can craft it manually.

Notes
- The included `backend/auth.php` will redirect to provider sign-in and exchange code for tokens (Google/Facebook).
- After successful login it stores user info in `$_SESSION['user']` and redirects to `DIMOB/index.html`.
- For production: validate `state` and add CSRF protections, use HTTPS, store secrets securely (not in repo), and validate provider tokens.

Local testing
- If you run locally, ensure your redirect URIs match exactly what's registered (including http vs https and trailing slashes).
- You may need to host the project in a local webserver (e.g., using PHP built-in server) so providers can redirect back.

Example to run PHP server from project root (Windows PowerShell):

```powershell
php -S localhost:8000 -t .
```

Then set redirect URIs like:
http://localhost:8000/DIMOB/backend/auth.php?provider=google

Security
- Never commit client secrets or private keys to public repos.
- Use environment variables or a safe server-side store in production.
