# Web Extension for TagTool_WiZArD application (ttw_webx)

## Introductory remarks

This web extension, that is starting to be developed steb-by-step based on `.php` scripts from v1.0.0 onward, can be used optionally to integrate TagTool_WiZArD application (ttw) (starting with ttw v1.3.0) into a web-compatible framework for small closed networks. 

(For TagTool_WiZArD application see https://github.com/pBxr/TagTool_WiZArd.)

**Important: Due to security reasons this web extension in the current version is explicitly not ment to be run on an open web server**. With no further modifications it should be used **only in a sealed off environment** for testing purposes (during this project `XAMPP` was used for example, see below).

## Features

- ttw_webx offers an upload menue for the article file and the `.csv` list as well as a menue to select the function parameters of ttw using usual `.html` forms.
- ttw_webx runs the necessary conversion of the article file with pandoc to `.html`, so the original `.docx` version can be uploaded directly.
- The result as well as the uploaded `.csv` value lists can be displayed in a browser tab. Within a session some steps can be re-done using the back button, i. e. without repeating the whole process.
- ttw_webx provides a `.zip` folder containing the result to download.
- ttw_webx is creating a separate temp folder for each browser session so it can deal theoretically with more than one user at the same time. 
- To avoid garbage ttw_webx deletes the temp folders of the expired sessions (in the current setting: of the previous day and earlier) automatically after starting.   

## To be done

- Improvement of security
- Consolidation of a multi user mode
- The aim of the next steps is to make the `.csv` value lists as well as the converted article file editable in a browser box using `JavaScript` to avoid the re-upload of files in case of mistakes.
- Exception and error handling 

## Mode of operation / technical remarks

- Install a **local and restricted** testing environment (e. g. `XAMPP`, see below)
- Copy the complete ttw_webx folder to htdocs directory
- Copy tagtool_v1-3-0.exe (or higher versions) together with the mandatory folder "resources" into the folder "ttw" (see https://github.com/pBxr/TagTool_WiZArd)
- Make sure that tagtool_v1-3-0.exe (or higher versions) has all necessary permissions to write.
- pandoc version 2.16.2 must be installed

## Tested with
- XAMPP 8.0.13-0 windows-x64