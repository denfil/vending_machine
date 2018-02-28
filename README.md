# Vending machine simulator

Customer deposits money by pressing button `deposit` of corresponding coin in wallet.
Then presses button of product to buy.
If deposited money is not enough to buy selected product, it'll show message `Deposited money is not enough`.
Customer at any time could press `Withdraw` to withdraw deposited money.

Initial state of customer wallet:
- 10 coins of 1 rub
- 30 coins of 2 rub
- 20 coins of 5 rub
- 15 coins of 10 rub

Initial state of vending machine wallet:
- 100 coins of 1 rub
- 100 coins of 2 rub
- 100 coins of 5 rub
- 100 coins of 10 rub

Vending machine products:
- Tea, 10 cups, 13 rub/cup
- Сoffee, 20 cups, 18 rub/cup
- Сoffee with milk, 20 cups, 21 rub/cup
- Juice, 15 cups, 35 rub/cup

## Installation

Build Docker image
``` bash
$ make install
```

Star application
``` bash
$ make start
```

Open [http://localhost:8080/](http://localhost:8080/) in your browser.

Stop application
``` bash
$ make stop
```

