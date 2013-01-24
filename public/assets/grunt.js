module.exports = function(grunt) {

  var css = [];
  css['login'] = {
    "src" : [
    "css/h5bp/normalize.css", 
    "css/h5bp/print.styles.css", 
    "css/sprites.css",
    "css/navigation.css",
    "css/typographics.css",
    "css/content.css",
    "css/footer.css",
    "css/sprite.forms.css",
    "css/ie.fixes.css",
    // "css/font-awesome.css",
    "css/special-page.css"
    ],
    "dist": "css/dist/login.min.css"
    
  }
  page = 'login';
  // Project configuration.
  grunt.initConfig({
    cssmin: {
      my_target: {
        src: css[page].src,
        dest: css[page].dist
      }
    }
    
  });

  // Load tasks from "grunt-sample" grunt plugin installed via Npm.
  grunt.loadNpmTasks('grunt-css');

  // Default task.
  grunt.registerTask('default', 'cssmin');
};