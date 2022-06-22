<?php

class FinalResult {
    function results($file) {
		$rcs = [];
		$contents = array_map("str_getcsv", file($file));
        [$currency, $failure_code, $failure_message] = array_shift($contents);

		foreach ($contents as $record):
			if (count($record) > 11) {
				$rcs[] = [
					"amount" => [
						"currency" => $currency,
						"subunits" => !$record[8] || $record[8] == "0" ? 0 : (int) ($record[8] * 100)
					],
					"bank_account_name" 	=> str_replace(" ", "_", strtolower($record[7])),
					"bank_account_number" 	=> !$record[6] ? "Bank account number missing" : (int) $record[6],
					"bank_branch_code" 		=> !$record[2] ? "Bank branch code missing" : $record[2],
					"bank_code" 			=> $record[0],
					"end_to_end_id" 		=> !$record[10] && !$record[11] ? "End to end id missing" : $record[10] . $record[11]
				];
			}
		endforeach;

		return [
            "filename" 			=> basename($file),
            "failure_code" 		=> $failure_code,
            "failure_message"	=> $failure_message,
            "records" 			=> $rcs
        ];
    }
}

/* class FinalResult {
    function results($file) {
        $rcs = [];
        $opened_file = fopen($file, "r");
        [$currency, $failure_code, $failure_message] = fgetcsv($opened_file);

        while(!feof($opened_file)) {
            $record = fgetcsv($opened_file);

            if(count($record) == 16) {
                $amt = !$record[8] || $record[8] == "0" ? 0 : (int) ($record[8] * 100);
                $ban = !$record[6] ? "Bank account number missing" : (int) $record[6];
                $bbc = !$record[2] ? "Bank branch code missing" : $record[2];
                $e2e = !$record[10] && !$record[11] ? "End to end id missing" : $record[10] . $record[11];

				$rcs[] = [
                    "amount" => [
                        "currency" => $currency,
                        "subunits" => $amt
                    ],
                    "bank_account_name"		=> str_replace(" ", "_", strtolower($record[7])),
                    "bank_account_number" 	=> $ban,
                    "bank_branch_code" 		=> $bbc,
                    "bank_code" 			=> $record[0],
                    "end_to_end_id" 		=> $e2e,
                ];
            }
        }

		return [
            "filename" 			=> basename($file),
            "document" 			=> $opened_file,
            "failure_code" 		=> $failure_code,
            "failure_message"	=> $failure_message,
            "records" 			=> $rcs
        ];
    }
} */
?>
