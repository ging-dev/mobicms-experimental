<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="mobiCMS | Errors">
    <title>mobiCMS | <?= ucwords($this->handlerType) ?></title>
    <style>
        body {
            margin: 20px 0;
            background-color: #EEEEEE;
            font: 13px/1.231 arial, helvetica, clean, sans-serif;
            font-family: "Myriad Pro", "Segoe UI", Helvetica, Arial, sans-serif;
        }

        h1, p {
            margin: 10px;
        }

        #container {
            margin: auto;
            max-width: 950px;
        }

        #message {
            margin-bottom: 30px;
            background: #E9DDDD none;
            padding: 5px;
            border: 3px solid red;
        }

        #message h1 {
            color: #a34b4b;
            font-size: 167%;
            font-weight: normal;
            font-style: normal;
        }

        .counter {
            background-color: gray;
            color: #FFFFFF;
            display: table-cell;
            vertical-align: middle;
            font-weight: bold;
            padding: 8px 12px;
        }

        .stackTrace {
            padding-bottom: 12px;
        }

        .stackTrace .info {
            border-bottom: 1px solid #9c9c9c;
            box-shadow: 0 3px 3px rgba(0, 0, 0, 0.2);
            background-color: #cecece;
        }

        .stackTrace h1 {
            color: gray;
            padding-top: 10px;
            font-size: 146.5%;
        }

        .stackTrace .fileinfo {
            display: table-cell;
            padding: 8px;
        }

        .stackTrace .trace {
            padding-bottom: 4px;
            background-color: #FAFAFA;
            border: 1px solid #9c9c9c;
        }

        .stackTrace .trace p {
            color: gray;
            font-size: .8em;
        }

        /* GeSHi's rectification */
        ol {
            overflow: auto;
            padding-left: 44px;
            padding-right: 40px;
        }
    </style>
</head>
<body>
<div id="container">
    <div id="message">
        <h1><?= strtoupper($this->handlerType) ?>: <?= $this->type ?></h1>

        <p><?= $this->message ?></p>
    </div>
