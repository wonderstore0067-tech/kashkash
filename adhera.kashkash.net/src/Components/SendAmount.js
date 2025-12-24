import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import {
  Client,
  PrivateKey,
  AccountId,
  TransferTransaction,
  TokenAssociateTransaction,
  TokenId,
  CustomFixedFee
} from "@hashgraph/sdk";

import Swal from "sweetalert2";

function SendAmount() {

  const profile = localStorage.getItem('user');
  let user_id = 0;
  if(profile)
  {
    console.log("profile", profile);
    user_id = JSON.parse(profile).data.id;
  }

  // let first_association = 0;
  // let second_association = 0;

  useEffect(() => {
    fetch(`https://adhera.kashkash.net/src/backend/single_user.php?id=${user_id}`)
    .then((response) => response.json())
    .then((data) => {
        console.log(data);
        setMyAccountId(data[0].account_id); // Set the newAccountId state
        setMyAccountPrivateKey(data[0].private_key); // Set the newAccountPrivateKey state
    })
    .catch((error) => {
        console.error(error);
    });
  }, [user_id]);



    const [myAccountId, setMyAccountId] = useState("");
    const [myPrivateKey, setMyAccountPrivateKey] = useState("");
    const [receiptText, setReceiptText] = useState("");
    const [isLoading, setIsLoading] = useState(false);
    const [newAccountId, setNewAccountId] = useState("");
    const [newAccountPrivateKey, setNewAccountPrivateKey] = useState("");
    const [amount, setAmount] = useState(1000);

    // const [associated, setAssociated] = useState("");

    const [firstAssociation, setFirstAssociation] = useState(0);
    const [secondAssociation, setSecondAssociation] = useState(0);

    const [receiptArray, setReceiptArray] = useState([]);


    useEffect(() => {
      fetch(`https://adhera.kashkash.net/src/backend/check_association.php?id=${myAccountId}`)
      .then((response) => response.json())
      .then((data) => {
        setFirstAssociation(data.value);
        console.log("associated", data.value);
        // console.log("association_count", first_association);
        // setAssociated(data[0].account_id); // Set the associated state
      })
      .catch((error) => {
          console.error(error);
      });
    }, [myAccountId]);


    useEffect(() => {
      fetch(`https://adhera.kashkash.net/src/backend/check_association.php?id=${newAccountId}`)
      .then((response) => response.json())
      .then((data) => {
        setSecondAssociation(data.value);
        console.log("new associated", data.value);
        // console.log("new association_count", second_association);
        // setAssociated(data[0].account_id); // Set the associated state
      })
      .catch((error) => {
          console.error(error);
      });
    }, [newAccountId]);

  

  const transferTokens = async () => {

    setIsLoading(true);
    
    if (myAccountId == null || myPrivateKey == null) {
      throw new Error(
        "Environment variables myAccountId and myPrivateKey must be present"
      );
    }

    try {
      // Initialize the clients
      // const myAccountId = AccountId.fromString("0.0.486740");
      // const myPrivateKey = PrivateKey.fromString("302e020100300506032b6570042204203c17a56b3c033cbaecdafb15ca1d1846831e55fbb1b6c2ad648580e40be1d88f");
      const client = Client.forTestnet().setOperator(myAccountId, myPrivateKey);

      // const newAccountId = AccountId.fromString("0.0.486566");
      // const newAccountPrivateKey = PrivateKey.fromString("302e020100300506032b6570042204202e07a3ba01dc9da8b2cbd9acb1ebed8ff290295764646164a11a89e6d6dbc634");
      const receiverClient = Client.forTestnet().setOperator(newAccountId, newAccountPrivateKey);


      // Replace with the token ID you want to transfer
      const tokenIdToTransfer = TokenId.fromString("0.0.486746"); // Replace with the actual token ID

      // Check if both sender and receiver are associated with the token

      if(firstAssociation == 0){
        alert("first_association"+firstAssociation);
        let myAssociation = {'operator_id': myAccountId};

          fetch('https://adhera.kashkash.net/src/backend/add_association.php', {
            method: 'POST',
            body: JSON.stringify(myAssociation),
          })
            .then((response) => response.json())
            .then((data) => {
              console.log("Association Success");
            })
            .catch((error) => {
              console.error('Error in association:', error);
            });
        try {
          const associateSenderTx = new TokenAssociateTransaction()
            .setAccountId(myAccountId)
            .setTokenIds([tokenIdToTransfer]);
          await associateSenderTx.execute(client);


        } catch (error) {
          if (error.message.includes("TOKEN_ALREADY_ASSOCIATED_TO_ACCOUNT")) {
            console.log(`Token already associated with sender account.`);
          } else {
            console.error("Error associating token with sender:", error);
            return;
          }
        }
      }

      if(secondAssociation == 0){
        alert("second_association"+secondAssociation);
        let newAssociation = {'operator_id': newAccountId};

          fetch('https://adhera.kashkash.net/src/backend/add_association.php', {
            method: 'POST',
            body: JSON.stringify(newAssociation),
          })
            .then((response) => response.json())
            .then((data) => {
              console.log("Association Success");
            })
            .catch((error) => {
              console.error('Error in association:', error);
            });
        try {
          const associateReceiverTx = new TokenAssociateTransaction()
            .setAccountId(newAccountId)
            .setTokenIds([tokenIdToTransfer]);
          await associateReceiverTx.execute(receiverClient);

        } catch (error) {
          if (error.message.includes("TOKEN_ALREADY_ASSOCIATED_TO_ACCOUNT")) {
            console.log(`Token already associated with receiver account.`);
          } else {
            console.error("Error associating token with receiver:", error);
            return;
          }
        }
      }

      // Transfer tokens from sender to receiver
      const transferTx = new TransferTransaction()
        .addTokenTransfer(tokenIdToTransfer, myAccountId, -amount) // Transfer -100000 tokens from sender to receiver
        .addTokenTransfer(tokenIdToTransfer, newAccountId, amount) // Transfer +100000 tokens to receiver from sender
        // .setTokenFee(tokenIdToTransfer)
        .freezeWith(client);

        //Create a custom token fixed fee
        const customFee =new CustomFixedFee()
        .setAmount(1) // 1 token is transferred to the fee collecting account each time this token is transferred
        .setDenominatingTokenId(tokenIdToTransfer) // The token to charge the fee in
        .setFeeCollectorAccountId(newAccountId); // 1 token is sent to this account everytime it is transferred

        //Version: 2.0.30


      const transferTxResponse = await transferTx.execute(client);
      const transferReceipt = await transferTxResponse.getReceipt(client);

      const transferTxHash = transferTxResponse.transactionId.toString();
      // console.log(`Transaction ID: ${transferTxHash}`);
      setReceiptArray((prevArray) => [...prevArray, transferTxHash ]);
      
      // setReceiptArray((prevArray) => [...prevArray, transferReceipt.toString() ]);
      // console.log(`Tokens transferred: ${transferReceipt.status}`);

      setReceiptText(`Tokens transferred: ${transferReceipt.status}`);
      setReceiptText((prevReceipt) => `${prevReceipt}\nSender Account Id: ${myAccountId}`);
      setReceiptText((prevReceipt) => `${prevReceipt}\nReceiver account ID: ${newAccountId}`);
      setReceiptText((prevReceipt) => `${prevReceipt}\nAmount Transfered: ${amount}`);

      setReceiptArray((prevArray) => [...prevArray, newAccountId ]);
      setReceiptArray((prevArray) => [...prevArray, transferReceipt.status.toString() ]);
      setReceiptArray((prevArray) => [...prevArray, myAccountId ]);
      setReceiptArray(prevArray => [...prevArray, amount]);
      setReceiptArray(prevArray => [...prevArray, user_id]);
      
    } catch (error) {
      console.error("Error:", error);
      setReceiptText(`Error processing transaction: ${error.message}`);
    }

    setIsLoading(false);
  };

  const { id } = useParams();
  const [user, setUser] = useState([]);

  useEffect(() => {
    fetch(`https://adhera.kashkash.net/src/backend/single_user.php?id=${id}`)
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setUser(data);
        setNewAccountId(data[0].account_id); // Set the newAccountId state
        setNewAccountPrivateKey(data[0].private_key); // Set the newAccountPrivateKey state
      })
      .catch((error) => {
        console.error(error);
      });
  }, [id]);




  useEffect(() => {
    console.log('receiptArray', receiptArray);
    // Check if receiptArray has all three values
    if (receiptArray.length === 6) {
      // Make an API call to post receiptArray to the database
      fetch('https://adhera.kashkash.net/src/backend/add_transaction.php', {
        method: 'POST',
        body: JSON.stringify(receiptArray),
      })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                Swal.fire("Success", data.message, "success");
                setTimeout(() => {
                  // Redirect to the user-list route
                  window.location.href = '/transaction-list';
              }, 2500);
            } 
            else {
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch((error) => {
          console.error('Error in transaction:', error);
          Swal.fire("Error", "An error occurred", "error");
        });
    }
  }, [receiptArray]);


return (
    <div>
      <div className="records mt-3">
        <h2 className="text-center">Send Amount</h2>
        {user.map((user) => (
            <div className="App" key={user.id}>
               <div className="input-container">
                 <label htmlFor="newAccountId">Account ID:</label>
                 <input
                   type="text"
                   id="newAccountId"
                   readOnly
                   value={user.account_id}
                   onChange={(e) => setNewAccountId(e.target.value)}
                 />
               </div>
               <div className="input-container">
                 <label htmlFor="newAccountPrivateKey">Account Private Key:</label>
                 <input
                   type="text"
                   id="newAccountPrivateKey"
                   readOnly
                   value={user.private_key}
                   onChange={(e) => setNewAccountPrivateKey(e.target.value)}
                 />
               </div>
               <div className="input-container">
                 <label htmlFor="amount">Amount:</label>
                 <input
                   type="number"
                   id="amount"
                   value={amount}
                   onChange={(e) => setAmount(Number(e.target.value))}
                 />
               </div>
               <button onClick={(myAccountId && myPrivateKey) ? transferTokens : () => alert('please update your profile')}>Send Payment</button>
               {isLoading && <div className="loader">Loading...</div>}
               {receiptText && (
                 <div className="receipt">
                   <h2>Receipt Details</h2>
                   <pre>{receiptText}</pre>
                   {receiptArray.map((item, index) => (
                    <pre key={index}>{JSON.stringify(item)}</pre>
                    ))}
                 </div>
               )}
             </div>
        ))}
      </div>
    </div>
  );

}

export default SendAmount;
