<!-- POST method to submit the form -->
<form action='{{ config('laravel-2c2p.access_url') }}' method='POST' name='paymentRequestForm'>
    Processing payment request, Do not close the browser, press back or refresh the page.
    <input type="hidden" name="paymentRequest" value="{{ $payload }}">
</form>
<script language="JavaScript">
    document.paymentRequestForm.submit();
</script>