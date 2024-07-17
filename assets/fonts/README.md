# Updating Icons

The main font used by MonsterInsights for icons has the font-family: 'Misettings'.

The files used by this font are

- assets/fonts/icons.eot
- assets/fonts/icons.otf
- assets/fonts/icons.ttf
- assets/fonts/icons.woff
- assets/fonts/icons.woff2

The font files are generated using [FortAwesome](https://fortawesome.com).

After generating new files, you'll need to update the files mentioned above & the css
used for the icons which can be found in assets/css/admin.css starting on line 135, look
for the class `.monstericon-`.
