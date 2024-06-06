<?
require_once("./lib/function.php");
$func = new Functions;

//$OrderFinalTotal='0.10'; //for testing
$DomainName = $_SERVER['HTTP_HOST'];

if($PaymentMethod == 1) // ipay88
{
	$AmountTmp = str_replace(".", "", str_replace(",", "", $OrderFinalTotal));
	$Signature = $func->iPay88_signature($PaymentKey . $PaymentUser . $OrderID . $AmountTmp . $PaymentCurr);
	$ResponseURL = $SiteUrl.$PaymentResponse;
	
	$payment_form = "<form method=\"post\" name=\"PaymentForm\" id=\"PaymentForm\" action=\"https://payment.ipay88.com.my/epayment/entry.asp\">
						<input type=\"hidden\" name=\"MerchantCode\" value=\"$PaymentUser\">
						<input type=\"hidden\" name=\"PaymentId\" value=\"16\">
						<input type=\"hidden\" name=\"RefNo\" value=\"$OrderID\">
						<input type=\"hidden\" name=\"Amount\" value=\"$OrderFinalTotal\">
						<input type=\"hidden\" name=\"Currency\" value=\"$PaymentCurr\">
						<input type=\"hidden\" name=\"ProdDesc\" value=\"Purchase from ".$DomainName.", Order Number: $OrderNo\">
						<input type=\"hidden\" name=\"UserName\" value=\"$BillName\">
						<input type=\"hidden\" name=\"UserEmail\" value=\"$BillEmail\">
						<input type=\"hidden\" name=\"UserContact\" value=\"$BillPhone\">
						<input type=\"hidden\" name=\"Remark\" value=\"$DomainName\">
						<input type=\"hidden\" name=\"Signature\" value=\"$Signature\">
						<input type=\"hidden\" name=\"ResponseURL\" value=\"$ResponseURL\">
						<input type=\"hidden\" name=\"BackendURL\" value=\"$SiteUrl/backend_response.php\">
					 </form>";
}
else if($PaymentMethod == 2) // paypal
{
	$ResponseURL = $SiteUrl.$PaymentResponse;
	
	$payment_form = "<form method=\"post\" name=\"PaymentForm\" id=\"PaymentForm\" action=\"https://www.paypal.com/cgi-bin/webscr\">
						<input type=\"hidden\" name=\"currency_code\" value=\"$PaymentCurr\">
						<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
						<input type=\"hidden\" name=\"business\" value=\"$PaymentUser\">
						<input type=\"hidden\" name=\"receiver_email\" value=\"$PaymentUser\">
						<input type=\"hidden\" name=\"item_name\" value=\"Purchase from $DomainName\">
						<input type=\"hidden\" name=\"item_number\" value=\"$OrderID\">
						<input type=\"hidden\" name=\"amount\" value=\"$OrderFinalTotal\">
						<input type=\"hidden\" name=\"no_shipping\" value=\"1\">
						<input type=\"hidden\" name=\"no_note\" value=\"1\">
						<input type=\"hidden\" name=\"charset\" value=\"utf-8\">
						<input type=\"hidden\" name=\"lc\" value=\"US\">
						<input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF\">
						<input type=\"hidden\" name=\"return\" value=\"".$ResponseURL."\">
						<input type=\"hidden\" name=\"cancel_return\" value=\"$SiteUrl/checkout-complete/$OrderID/\">
						<input type=\"hidden\" name=\"notify_url\" value=\"".$ResponseURL."\" />
						<input type=\"hidden\" name=\"rm\" value=\"2\">
					</form>";
}
else if($PaymentMethod == 5) // senangpay (customize)
{
	$securehash = md5($PaymentKey.urldecode($OrderNo).urldecode($OrderFinalTotal).urldecode($OrderID));

	$payment_form = "<form method=\"post\" name=\"paymentForm\" id=\"PaymentForm\" action=\"https://app.senangpay.my/payment/$PaymentUser\">
						<input type=\"hidden\" name=\"detail\" value=\"$OrderNo\">
						<input type=\"hidden\" name=\"amount\" value=\"$OrderFinalTotal\">
						<input type=\"hidden\" name=\"order_id\" value=\"$OrderID\">
						<input type=\"hidden\" name=\"name\" value=\"$BillName\">
						<input type=\"hidden\" name=\"email\" value=\"$BillEmail\">
						<input type=\"hidden\" name=\"phone\" value=\"$BillPhone\">
						<input type=\"hidden\" name=\"hash\" value=\"$securehash\">
					</form>";
}
else if($PaymentMethod == 6) // enets (customize)
{
	$AmountTmp = str_replace(".", "", str_replace(",", "", $OrderFinalTotal));

	$payment_form = "<form method=\"post\" name=\"paymentForm\" id=\"PaymentForm\" action=\"$SiteUrl/enets-payment/\">
						<input type=\"hidden\" name=\"PaymentAmount\" value=\"$AmountTmp\">
						<input type=\"hidden\" name=\"PaymentRef\" value=\"$OrderNo\">
						<input type=\"hidden\" name=\"PaymentCurr\" value=\"$PaymentCurr\">
					</form>";
}
?>