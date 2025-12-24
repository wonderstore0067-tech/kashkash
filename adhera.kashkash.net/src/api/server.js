const express = require('express');
const app = express();

app.get('/api/receipt', (req, res) => {
  const receipt = [];

  const {
    Client,
    PrivateKey,
    AccountCreateTransaction,
    AccountBalanceQuery,
    Hbar,
    TransferTransaction,
  } = require("@hashgraph/sdk");
  require("dotenv").config();

  async function environmentSetup() {
    // Grab your Hedera testnet account ID and private key from your .env file
    const myAccountId = process.env.MY_ACCOUNT_ID;
    const myPrivateKey = process.env.MY_PRIVATE_KEY;

    // If we weren't able to grab it, we should throw a new error
    if (myAccountId == null || myPrivateKey == null) {
      throw new Error(
        "Environment variables myAccountId and myPrivateKey must be present"
      );
    }

    // Create your connection to the Hedera network
    const client = Client.forTestnet();

    //Set your account as the client's operator
    client.setOperator(myAccountId, myPrivateKey);

    // Set default max transaction fee & max query payment
    client.setMaxTransactionFee(new Hbar(100));
    client.setMaxQueryPayment(new Hbar(50));

    // Create new keys
    const newAccountPrivateKey = PrivateKey.fromString('302e020100300506032b657004220420197ade93a1ff26fe55bd072ed27126cf1957c0922ccfdddd40ec389f762cd205');
    const newAccountPublicKey = newAccountPrivateKey.publicKey;

    // Create a new account with 1,000 tinybar starting balance
    const newAccountTransactionResponse = await new AccountCreateTransaction()
      .setKey(newAccountPublicKey)
      .setInitialBalance(Hbar.fromTinybars(1000000))
      .execute(client);

    const transactionId = newAccountTransactionResponse.transactionId;

    console.log("Transaction Id: " + transactionId);

    // Get the new account ID
    const getReceipt = await newAccountTransactionResponse.getReceipt(client);
    // const newAccountId = getReceipt.accountId;
    const newAccountId = '0.0.15148550';

    console.log("\nNew account ID: " + newAccountId);

    // Verify the account balance
    const accountBalance = await new AccountBalanceQuery()
      .setAccountId(newAccountId)
      .execute(client);

    console.log(
      "New account balance is: " +
      accountBalance.hbars.toTinybars() +
      " tinybars."
    );

    // Create the transfer transaction
    const sendHbar = await new TransferTransaction()
      .addHbarTransfer(myAccountId, Hbar.fromTinybars(-1000000))
      .addHbarTransfer(newAccountId, Hbar.fromTinybars(1000000))
      .execute(client);

    // Verify the transaction reached consensus
    const transactionReceipt = await sendHbar.getReceipt(client);
    console.log(
      "The transfer transaction from my account to the new account was: " +
      transactionReceipt.status.toString()
    );

    // Request the cost of the query
    const queryCost = await new AccountBalanceQuery()
      .setAccountId(newAccountId)
      .getCost(client);

    console.log("\nThe cost of query is: " + queryCost);

    // Check the new account's balance
    const getNewBalance = await new AccountBalanceQuery()
      .setAccountId(newAccountId)
      .execute(client);

    console.log(
      "The account balance after the transfer is: " +
      getNewBalance.hbars.toTinybars() +
      " tinybars."
    );

    receipt.push(
      "Transaction Id: " + transactionId,
      "New account ID: " + newAccountId,
      "New account balance is: " + accountBalance.hbars.toTinybars() + " tinybars.",
      "The transfer transaction from my account to the new account was: " + transactionReceipt.status.toString(),
      "The cost of query is: " + queryCost,
      "The account balance after the transfer is: " + getNewBalance.hbars.toTinybars() + " tinybars."
    );

    res.json({ receipt });
  }

  environmentSetup();
});

app.listen(5000, () => {
  console.log('Server is running on port 5000');
});
