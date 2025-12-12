# Vercel Environment Variables Setup

## Required Environment Variables

Add these environment variables in your Vercel project dashboard:

1. Go to your Vercel project
2. Navigate to **Settings** → **Environment Variables**
3. Add the following variables:

### Database Configuration

```
DB_HOST=sql7.freesqldatabase.com
DB_PORT=3306
DB_USER=sql7811650
DB_PASS=KBH2dX2Akh
DB_NAME=sql7811650
```

### Application Configuration

```
APP_ENV=production
DEBUG=false
SECURITY_SALT=your-random-salt-here-change-this
SECURITY_COOKIE_KEY=your-random-cookie-key-here-change-this
```

### Firebase Configuration (if using Firebase features)

```
FIREBASE_API_KEY=your-firebase-api-key
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com
```

## Setting Environment Variables

### Via Vercel Dashboard (Recommended)

1. Go to https://vercel.com/dashboard
2. Select your project
3. Go to **Settings** → **Environment Variables**
4. Add each variable:
   - **Key**: `DB_HOST`
   - **Value**: `sql7.freesqldatabase.com`
   - **Environment**: Select all (Production, Preview, Development)
5. Repeat for all variables above
6. Redeploy your project

### Via Vercel CLI

```bash
vercel env add DB_HOST
vercel env add DB_PORT
vercel env add DB_USER
vercel env add DB_PASS
vercel env add DB_NAME
vercel env add APP_ENV
vercel env add DEBUG
vercel env add SECURITY_SALT
vercel env add SECURITY_COOKIE_KEY
```

## Security Notes

⚠️ **IMPORTANT**: Never commit database credentials or secrets to git!

- Environment variables set in Vercel dashboard are secure and not exposed in code
- The `vercel.json` file does NOT contain sensitive credentials
- Always use environment variables for production secrets

## Testing Database Connection

After setting environment variables and redeploying, test your database connection by accessing your application. Check Vercel function logs if you encounter connection issues.

## Troubleshooting

### Database Connection Errors

- Verify all environment variables are set correctly
- Check that your database host allows connections from Vercel's IP addresses
- Ensure the database user has proper permissions
- Check Vercel function logs for detailed error messages

### Environment Variables Not Loading

- Make sure variables are set for the correct environment (Production/Preview/Development)
- Redeploy after adding new environment variables
- Check variable names match exactly (case-sensitive)

