# Vercel Deployment Guide

## Overview

This project uses Vercel's PHP runtime (`vercel-php@0.7.4`) which automatically handles Composer dependency installation.

## Important: Build Command Configuration

**DO NOT** set a custom build command in Vercel dashboard. The `vercel-php` runtime automatically:
- Detects `composer.json`
- Runs `composer install` automatically
- Handles PHP dependencies

## If You're Seeing "php: command not found" Error

This error typically occurs when:
1. A build command is configured in Vercel dashboard that tries to run PHP/Composer manually
2. The build command conflicts with Vercel's automatic Composer handling

### Solution 1: Remove Build Command in Vercel Dashboard

1. Go to your Vercel project settings
2. Navigate to **Settings** → **General** → **Build & Development Settings**
3. Check if there's a **Build Command** set
4. If there is, **remove it** or set it to empty
5. Save and redeploy

### Solution 2: Verify vercel.json Configuration

The `vercel.json` file should NOT have a `buildCommand` field. It should look like:

```json
{
  "version": 2,
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.7.4",
      ...
    }
  },
  ...
}
```

## Local Development

For local development, you need PHP installed:

### Option 1: Use XAMPP (Windows)

1. Install XAMPP from https://www.apachefriends.org/
2. Use XAMPP's PHP: `C:\xampp\php\php.exe composer.phar install`
3. See `QUICK_START.md` for detailed instructions

### Option 2: Install PHP Globally

1. Download PHP from https://windows.php.net/download/
2. Add PHP to your system PATH
3. Run: `php composer.phar install`

### Option 3: Use Docker

```bash
docker run --rm -v ${PWD}:/app -w /app php:8.0-cli php composer.phar install
```

## Environment Variables

Make sure to set these in Vercel dashboard (Settings → Environment Variables):

- `APP_ENV=production`
- Database connection variables (if needed)
- Firebase configuration (if needed)

## Deployment Checklist

- [ ] No build command in Vercel dashboard
- [ ] `vercel.json` is properly configured
- [ ] `composer.json` exists in project root
- [ ] Environment variables are set
- [ ] Database is accessible from Vercel (if using external DB)

## Troubleshooting

### Error: "php: command not found"
- **Cause**: Build command trying to run PHP manually
- **Fix**: Remove build command from Vercel dashboard

### Error: "Composer dependencies not installed"
- **Cause**: Vercel runtime not detecting composer.json
- **Fix**: Ensure composer.json is in project root and committed to git

### Error: "Runtime not found"
- **Cause**: vercel-php package not installed
- **Fix**: Run `npm install` to install `vercel-php` dependency

## Additional Resources

- [Vercel PHP Runtime Documentation](https://github.com/juicyfx/vercel-php)
- [Composer Documentation](https://getcomposer.org/doc/)

