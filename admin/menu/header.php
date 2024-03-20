<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!--  <link rel="stylesheet" href="../menu/menu.css">  -->
    <style>
body, h1, h2, h3, p, ul, li {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}


header {
    background-color: #dae6e2;
    color: white;
    padding: 10px;
    text-align: center;
    height: 60px;
}

header button{
    margin-left:1200px;
    margin-top:10px;
    background-color: #426056;
    padding:7px 12px;
    border-radius:5px;
    position: relative;
    top: -60px;
}

button a{
    color:white;
    text-decoration:none;

}

aside {
    width: 200px;
    height: 85vh;
    background-color: #808080;
    color: white;
    padding: 20px;
    float: left;
    margin-right: 50px;
}

.side-menu {
    list-style-type: none;
}

.side-menu li {
    margin-bottom: 20px;
}

.side-menu a {
    color: white;
    text-decoration: none;
}

main {
    margin-left: 220px;
    padding: 20px;
    background-color: #fff;
}

footer {
    clear: both;
    text-align: center;
    background-color: #dae6e2;
    color: white;
    padding: 10px;
}

    </style>
</head>
<body>
    <header>
        <h1>Welcome to Admin Dashboard</h1>
        <button><a href="../logout.php">Logout</a></button>
    </header>
    