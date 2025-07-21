# QCubed Slider Plugin

## Slider for QCubed-4


This QCubed plugin allows you to create sliders for any website. It utilizes the bxSlider plugin: https://bxslider.com.

In this example, there are 2 files: slider_manager.php for users/editors, and slider.php for the frontend.

QCubed-4 Slider uses the FileManager service, so it's recommended to download it beforehand: https://github.com/kukrik/qcubed-filemanager.

Of course, you'll also need to download the database tables from the "database" folder. From the same folder "project-includes-model", you'll need to move these classes to the "project/includes/model" folder.

This plugin works starting from PHP version 8.3+.

![Image of kukrik](screenshot/sliders.jpg?raw=true)


If you have not previously installed QCubed Bootstrap and twitter bootstrap, run the following actions on the command 
line of your main installation directory by Composer:
```
    composer require twbs/bootstrap v3.3.7
```
and

```
    composer require kukrik/qcubed-filemanager
    composer require kukrik/bootstrap-filecontrol
    composer require kukrik/qcubed-slider
    composer require qcubed-4/plugin-bootstrap
```

