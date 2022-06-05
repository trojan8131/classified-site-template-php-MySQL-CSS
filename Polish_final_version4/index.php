<?php
session_start();
include("notebook.php");
$notebook = new Notebook( );
$notebook->makepage( $notebook->process() );
