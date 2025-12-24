import React, { useEffect } from "react";
import { Client, PrivateKey, AccountId, TokenCreateTransaction, TokenType, TokenSupplyType, TokenAssociateTransaction, TokenInfoQuery } from "@hashgraph/sdk";

function SendAmount() {
  useEffect(() => {
    checkAndCreateFungibleToken();
  }, []);

  const checkAndCreateFungibleToken = async () => {
    try {
      // Initialize the client
      const operatorId = AccountId.fromString("0.0.486740");
      const operatorKey = PrivateKey.fromString("302e020100300506032b6570042204203c17a56b3c033cbaecdafb15ca1d1846831e55fbb1b6c2ad648580e40be1d88f");
      const client = Client.forTestnet().setOperator(operatorId, operatorKey);

      // Replace with the token ID you want to check
      const tokenIdToCheck = "0.0.486746"; // Replace with the token ID you want to check
      const tokenInfoQuery = new TokenInfoQuery().setTokenId(tokenIdToCheck);

      try {
        // Execute the query to check token existence
        const tokenInfo = await tokenInfoQuery.execute(client);

        if (tokenInfo.tokenId.toString() === tokenIdToCheck) {
          console.log(`Token with ID ${tokenIdToCheck} already exists.`);
        } else {
          // Create the token if it doesn't exist
          await createFungibleToken(client, operatorId);
        }
      } catch (error) {
        // Create the token if the query results in an error (token does not exist)
        await createFungibleToken(client, operatorId);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  const createFungibleToken = async (client, operatorId) => {
    try {
      // Create token
      const tokenCreateTx = new TokenCreateTransaction()
        .setTokenName("MyToken")
        .setTokenSymbol("MT")
        .setTokenType(TokenType.FungibleCommon)
        .setDecimals(18) // Set appropriate decimal places
        .setInitialSupply(1000000000000000000) // Initial token supply
        .setTreasuryAccountId(operatorId)
        .setSupplyType(TokenSupplyType.Infinite)
        .freezeWith(client);

      const tokenCreateTxResponse = await tokenCreateTx.execute(client);
      const tokenCreateReceipt = await tokenCreateTxResponse.getReceipt(client);
      const tokenId = tokenCreateReceipt.tokenId.toString();
      console.log(`Token created with ID: ${tokenId}`);

      // Associate token with operator account
      const associateTx = new TokenAssociateTransaction()
        .setAccountId(operatorId)
        .setTokenIds([tokenId]);

      const associateTxResponse = await associateTx.execute(client);
      const associateReceipt = await associateTxResponse.getReceipt(client);
      console.log(`Token associated with account: ${associateReceipt.status}`);
    } catch (error) {
      console.error("Error creating token:", error);
    }
  };

  return <div>Create Token</div>;
}

export default SendAmount;
