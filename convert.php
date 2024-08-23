<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user inputs
    $amount = htmlspecialchars($_POST['amount']);
    $from_currency = strtoupper(htmlspecialchars($_POST['from_currency']));
    $to_currency = strtoupper(htmlspecialchars($_POST['to_currency']));

    // Validate that the amount is numeric and positive
    if (is_numeric($amount) && $amount > 0) {
        // API key
        $apiKey = 'your_actual_api_key_here';
        $apiUrl = "https://v6.exchangerate-api.com/v6/60461589c2e8063cce73b0ca/latest/$from_currency";

        // Exchange rates
        $response = file_get_contents($apiUrl);

        if ($response !== false) {
            $data = json_decode($response, true);

            // Check request was successful
            if ($data && $data['result'] == 'success') {
                $rates = $data['conversion_rates'];

                // Check if the target currency is supported
                if (isset($rates[$to_currency])) {
                    $rate = $rates[$to_currency];
                    $converted_amount = $amount * $rate;

                    // Display the conversion result
                    echo "<h2>Conversion Result</h2>";
                    echo "<p>$amount $from_currency = $converted_amount $to_currency</p>";
                } else {
                    echo "Error: Invalid target currency code.";
                }
            } else {
                echo "Error: Failed to retrieve exchange rates.";
            }
        } else {
            echo "Error: Unable to connect to the currency API.";
        }
    } else {
        echo "Error: Please enter a valid, positive amount.";
    }
} else {
    echo "Error: Invalid request method. Please submit the form.";
}
?>
