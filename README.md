# Starter WebPack

https://github.com/Harrix/static-site-webpack-habr/blob/main/docs/article.md

Here is a simple and easy starter web project.

The site is a simple set of HTML pages with its own CSS styles and a JavaScript file. It is necessary to write a project that would assemble our site from source:

- one CSS file is formed from SASS (more precisely SCSS) files;
- a single JavaScript file is generated from various JavaScript libraries and user code;
- HTML pages are assembled using a template, where the contents of the header and footer can be separated into separate files.

## How to start

Run `npm install`

Run `npm run dev` for a dev server. Navigate to `http://localhost:3000/`. The app will automatically reload if you change any of the source files.

Run `npm run build` to build the project. We are assembling the project and the assembled static site will appear in the `dist` folder.

## Project structure

The general structure of the project is presented below:

```
├── dist
├─┬ src
│ ├─┬ favicon
│ │ └── favicon.ico
│ ├─┬ fonts
│ │ └── Roboto-Regular.ttf
│ ├─┬ html
│ │ ├─┬ parts
│ │ │ ├── footer.html
│ │ │ └── header.html
│ │ └─┬ pages
│ │   ├── index.html
│ │   └── second.html
│ ├─┬ img
│ │ └── logo.svg
│ ├─┬ js
│ │ └── index.js
│ ├─┬ scss
│ │ └── style.scss
├── package.json
└── webpack.config.js
```
