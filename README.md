
### Require

* docker
* docker-compose

### Install
```angular2html
make init
```

### SQL query

```angular2html
select currency, sum(amount)
from transactions
where date(created_at) > (date(now()) - 7) and reason = 'refund'
group by currency;
```

### Get wallet balance
```angular2html
curl --location --request GET 'localhost:8080/v1/wallet/balance?id=3' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer secret_token' \
--data '{
    "walletId":2,
    "type": "debit",
    "currency": "USD",
    "amount": 100,
    "reason": "stock"
}'
```

### Update balance
```angular2html
curl --location 'localhost:8080/v1/wallet/transaction' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer secret_token' \
--data '{
    "walletId":2,
    "type": "debit",
    "currency": "USD",
    "amount": 100,
    "reason": "stock"
}'
```