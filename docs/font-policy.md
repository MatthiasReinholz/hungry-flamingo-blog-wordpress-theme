# Font Policy

The theme does not load runtime fonts from external services.

## Theme Fonts

Theme typography uses local CSS font stacks declared in `theme.json`. If a project wants to bundle actual font files with the theme, add the files to the repository only when the font license allows redistribution and update `THIRD-PARTY-NOTICES.md`.

## Site Fonts

For site-specific uploaded fonts, use WordPress core's Font Library. Those files are site content, not theme source code, and should live in the WordPress uploads/fonts location managed by WordPress.

## Companion Plugin

Do not add a parallel font uploader to the companion plugin. Font upload, activation, and storage are site-design concerns already covered by WordPress core.
