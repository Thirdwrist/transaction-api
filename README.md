Okay
Here is your test
Imagine you are asked to develop a transfer service with APIs to transfer money between two accounts
You application is expected to have the following database structure

TABLE transactions
  - reference (unique)
  - amount
  - account nr

TABLE balances
  - account nr (unique)
  - balance
The transaction table registers any transaction in an account (ie. today I paid N2000 for a movie with my card), the balances table represents the account balance of customers (ie. I have N50k in my bank account).

Assume that the database is a relational database like MYSQL

You are expected to have a near production ready code.

The API you are to develop should be able to handle a transfer request of the form {from: account, to: account, amount: money} and updates the transactions / balances table accordingly.

A few things to keep in mind:

you will receive requests from  mobile app, that customers use to transfer money around
can you deal with the fact that one customer might tap on the "transfer" button twice, by mistake?
what happens if your database becomes unavailable in the middle of your logic?
what happens if 2 users (A, B) transfer money to user C at the same time?
how will you handle transaction validity and consistency
the only thing you will not need to validate is that the user is authorized to perform this API request. let's assume the user is logged in and carries a cookie, with the transfer request, that ensures it comes from a legit source
You have 48hrs to finish it
