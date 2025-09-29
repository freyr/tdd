* Gdy istnieje PENDING zamówienie, wywołanie checkout(orderId, method, amount):
 - Zleca obciążenie bramce (PaymentGateway.charge) z idempotency key. 
 - Zapisuje wynik w repo (+ status PAID lub FAILED, trace id). 
 - Zwraca Receipt (status, kwota, transactionId, maskedCard).

* Błędy sieci mapują się na status RETRYABLE_FAILURE; błędne dane na PERMANENT_FAILURE.