<style>
    /*cart*/
    .quantity {
        position: relative;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button
    {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number]
    {
        -moz-appearance: textfield;
    }

    .quantity input {
        width: 66px;
        height: 42px;
        line-height: 1.65;
        float: left;
        display: block;
        padding: 0;
        margin: 0;
        padding-left: 20px;
        border: 1px solid #eee;
    }

    .quantity input:focus {
        outline: 0;
    }

    .quantity-nav {
        float: left;
        position: relative;
        height: 42px;
    }

    .quantity-button {
        position: relative;
        cursor: pointer;
        border-left: 1px solid #eee;
        width: 20px;
        text-align: center;
        color: #333;
        font-size: 13px;
        font-family: "Trebuchet MS", Helvetica, sans-serif !important;
        line-height: 1.7;
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .quantity-button.quantity-up {
        position: absolute;
        height: 50%;
        top: 0;
        border-bottom: 1px solid #eee;
    }

    .quantity-button.quantity-down {
        position: absolute;
        bottom: -1px;
        height: 50%;
    }



/*paymentgateway*/
    .wrap_radioinsure {
        display: none;
        margin-top: -5px;
    }

    .wrap_radioinsure01 {
        display: none;
        margin-top: -5px;
    }

    .wrap_radioinsure02 {
        display: none;
        margin-top: -5px;
    }

    .wrap_radioinsure03 {
        display: none;
        margin-top: -5px;
    }

    .wrap_radioinsure04 {
        display: none;
        margin-top: -5px;
    }

    .md-radio.md-radio-inline {
        display: block;
    }

    .md-radio input[type="radio"] {
        display: none;
    }

    .md-radio input[type="radio"]:checked+label:before {
        border-color: #222;
        animation: ripple 0.2s linear forwards;
        background-color: #fff;
    }

    .md-radio input[type="radio"]:checked+label:after {
        transform: scale(1);
    }

    .md-radio label {
        display: block;
        height: 20px;
        position: relative;
        padding: 0 30px;
        margin-bottom: 0;
        cursor: pointer;
        vertical-align: bottom;
    }

    .md-radio label:before,
    .md-radio label:after {
        position: absolute;
        content: '';
        border-radius: 50%;
        transition: all .3s ease;
        transition-property: transform, border-color;
    }

    .md-radio label:before {
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border: 2px solid #222;
        background-color: #fff;
    }

    .md-radio label:after {
        top: 5px;
        left: 5px;
        width: 10px;
        height: 10px;
        transform: scale(0);
        background: #222;
    }



</style>