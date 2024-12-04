# Resonato
A music player that plays music from external drives.

## Usage
Add your drives to the .env file.
```
EXTERNAL_DRIVES=[{"path":"/Volumes/drive1/music","identifier":"1"},{"path":"/Volumes/drive2/music","identifier":"2"}]
```

Add symbolic links to the drives.
```
ln -s /Volumes/drive1/music /path/to/project/Resonato/public/music1
ln -s /Volumes/drive2/music /path/to/project/Resonato/public/music2
```

Run the scan command to scan the drives.
```
php artisan music:scan
```

Open the app in your browser.

## License

[MIT](https://opensource.org/licenses/MIT).
