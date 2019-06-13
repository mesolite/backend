<?php

function TCPDF2DBarcode($arg)
{
    return new TCPDF2DBarcode(...func_get_args());
}

function TCPDFBarcode($arg)
{
    return new TCPDFBarcode(...func_get_args());
}
