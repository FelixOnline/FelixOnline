// please run npm install hogan.js first

var hogan = require('hogan.js')
  , fs = require('fs');

var templates,
  output = 'T={};';

// retrieve pages
templates = fs.readdirSync(__dirname + '/..');

// iterate over pages
templates.forEach(function (name) {

  if (!name.match(/\.mustache$/)) return

  var template = fs.readFileSync(__dirname  + '/../' + name, 'utf-8');

  var id = name.replace(/\.mustache$/, '');
  var compiled =  hogan.compile(template.toString(), {asString : true});
  output += "\nT."+id+"="+compiled;

})

output += "\nwindow.T = T;"

fs.writeFileSync(__dirname + '/../template.js', output, 'utf-8');

