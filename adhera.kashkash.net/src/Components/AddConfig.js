import React, { useState, useEffect } from "react";
import { Client, PrivateKey, AccountId, TokenCreateTransaction, TokenType, TokenSupplyType, TokenAssociateTransaction, TokenInfoQuery, TokenMintTransaction, AccountBalanceQuery } from "@hashgraph/sdk";
import Swal from "sweetalert2";
import Header from "./Header";

function AddConfig() {
  const [tokenName, setTokenName] = useState("");
  const [tokenSymbol, setTokenSymbol] = useState("");
  const [initialSupply, setInitialSupply] = useState("");
  const [myOperatorId, setMyOperatorId] = useState("");
  const [myOperatorKey, setMyOperatorKey] = useState("");
  const [myPublicKey, setMyPublicKey] = useState("");
  const [hexPrivateKey, setHexPrivateKey] = useState("");
  const [selectedImage, setSelectedImage] = useState(null);
  const [imageURL, setImageURL] = useState("");
  const [recordId, setRecordId] = useState("");


  var tokenType = '';
  var decimal = '';
  var adminKey = '';
  var freezeKey = '';
  var defaultFrauzen = '';
  var wipeKey = '';
  var supplyManage = '';

  const profile = localStorage.getItem('user');
  let user_id = 0;
  if(profile)
  {
    // console.log("profile", profile);
    user_id = JSON.parse(profile).data.id;
  }

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    setSelectedImage(file);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('tokenName', tokenName);
    formData.append('tokenSymbol', tokenSymbol);
    formData.append('initialSupply', initialSupply);
    formData.append('myOperatorId', myOperatorId);
    formData.append('myOperatorKey', myOperatorKey);
    formData.append('myPublicKey', myPublicKey);
    formData.append('hexPrivateKey', hexPrivateKey);
    formData.append('user_id', user_id);
    formData.append('image', selectedImage); // Append the image file
    formData.append('tokenType', tokenType);
    formData.append('decimal', decimal);
    formData.append('adminKey', adminKey);
    formData.append('freezeKey', freezeKey);
    formData.append('defaultFrauzen', defaultFrauzen);
    formData.append('wipeKey', wipeKey);
    formData.append('supplyManage', supplyManage);
            
    const operatorId = AccountId.fromString(myOperatorId);
    const operatorKey = PrivateKey.fromString(myOperatorKey);
    const client = Client.forTestnet().setOperator(operatorId, operatorKey);

    const query = new AccountBalanceQuery()
     .setAccountId(myOperatorId);
    const accountBalance = await query.execute(client);

    const balanceString = accountBalance.hbars.toString();
    const numericString = balanceString.replace(/[^\d.]/g, '');
    const balanceFloat = parseFloat(numericString);
    const balanceInteger = Math.round(balanceFloat); 

    if (balanceInteger > 14) {
    fetch("https://adhera.kashkash.net/src/backend/add_config.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // console.log(data);
        if (data.status === "success") {
          setRecordId(data.id);
          setImageURL(data.image);
          // Swal.fire("Success", data.message, "success");
          // // console.log(imageURL);
          // // console.log(recordId);
          // // checkAndCreateFungibleToken();

          //   setTimeout(() => {
          //       window.location.href = '/config';
          //   }, 12000);
        } else {
          Swal.fire("Error", data.message, "error");
        }
      })
      .catch((error) => {
        console.error(error);
        Swal.fire("Error", "An error occurred", "error");
      });

      }
      else{
        Swal.fire("Error", "Insufficient Balance", "error");
      }
  };

  useEffect(() => {
    // This useEffect will be triggered whenever imageURL changes
    if (imageURL !== "") {
      // console.log(recordId);
      checkAndCreateFungibleToken();
    }
  }, [imageURL]);

  const checkAndCreateFungibleToken = async () => {
    try {
      // Initialize the client
      const operatorId = AccountId.fromString(myOperatorId);
      const operatorKey = PrivateKey.fromString(myOperatorKey);
      const client = Client.forTestnet().setOperator(operatorId, operatorKey);

      // Replace with the token ID you want to check
      const tokenIdToCheck = "0.0.486746"; // Replace with the token ID you want to check
      const tokenInfoQuery = new TokenInfoQuery().setTokenId(tokenIdToCheck);

      await createFungibleToken(client, operatorId, operatorKey);

    } catch (error) {
      console.error("Error:", error);
    }
  };

  const createFungibleToken = async (client, operatorId, operatorKey) => {
    try {
        // let imageURL = "https://adhera.kashkash.net/fav-icon.png"; // replace with your image URL
        let newURL = "https://adhera.kashkash.net/src/backend/"+ imageURL;

        // console.log(newURL);

         // Set the administrator key
        const adminPrivateKey = PrivateKey.fromString(myOperatorKey) // Replace with your administrator private key
        const adminPublicKey = adminPrivateKey.publicKey;

        // Create token
        const tokenCreateTx = await new TokenCreateTransaction()
            .setTokenName(tokenName ? tokenName : "MyToken")
            .setTokenSymbol(tokenSymbol ? tokenSymbol : "MT")
            .setTokenType(TokenType.FungibleCommon)
            .setDecimals(10)
            .setInitialSupply(initialSupply ? initialSupply : 1000000000) // Initial token supply
            .setTreasuryAccountId(operatorId)
            .setSupplyKey(operatorKey)
            .setWipeKey(operatorKey)
            .setAdminKey(adminPublicKey)
            .setFreezeKey(operatorKey)
            .setSupplyType(TokenSupplyType.Infinite)
            .setTokenMemo(newURL)
            .freezeWith(client);

            //Sign the transaction with the token adminKey and the token treasury account private key
            const signTx = await tokenCreateTx.sign(adminPrivateKey);

        //Sign the transaction with the client operator private key and submit to a Hedera network
        const txResponse = await signTx.execute(client);

        const tokenCreateTxResponse = await tokenCreateTx.execute(client);
        const tokenCreateReceipt = await tokenCreateTxResponse.getReceipt(client);
        const tokenId = tokenCreateReceipt.tokenId.toString();
        console.log(`Token created with ID: ${tokenId}`);
        
        let mintTx = await new TokenMintTransaction()
            .setTokenId(tokenId)
            .setMetadata([Buffer.from(newURL)])
            .execute(client);

        // Wait for the transaction to be consensus-reached
        await mintTx.getReceipt(client);

        // Retrieve token information
        const tokenInfo = await new TokenInfoQuery()
            .setTokenId(tokenId)
            .execute(client);

        console.log("Token Metadata:", tokenInfo);

        if(tokenId !== "")
        {
          var tokenNo = tokenId;
          var id = recordId;

          var tokenType = '';
          var decimal = '';
          var adminKey = '';
          var freezeKey = '';
          var defaultFrauzen = '';
          var wipeKey = '';
          var supplyManage = '';

          // Create an object with the form data
          const formData = {
            tokenName,
            tokenSymbol,
            initialSupply,
            tokenNo,
            myOperatorId,
            myOperatorKey,
            myPublicKey,
            hexPrivateKey,
            id,
            tokenType,
            decimal,
            adminKey,
            freezeKey,
            defaultFrauzen,
            wipeKey,
            supplyManage
            };

          // Send the form data to the backend
          fetch("https://adhera.kashkash.net/src/backend/update_config.php", {
          method: "POST",
          body: JSON.stringify(formData),
          })
          .then((response) => response.json())
          .then((data) => {
              console.log(data);

              Swal.fire("Success", data.message, "success");
              // console.log(imageURL);
              // console.log(recordId);
              // checkAndCreateFungibleToken();

                setTimeout(() => {
                    window.location.href = '/config';
                }, 15000);
          });
        }

    } catch (error) {
        console.error("Error creating token:", error);
    }
};

  return (
      <div>
        <Header />
        <form className="form-container mt-3" onSubmit={handleSubmit}>
        <h3 className="text-center">CREATE A COIN</h3>
        <div>
            <label htmlFor="myOperatorId">Account Id:</label>
            <input
            type="text"
            id="myOperatorId"
            required
            value={myOperatorId}
            onChange={(e) => setMyOperatorId(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="myOperatorKey">Private Key:</label>
            <input
            type="text"
            id="myOperatorKey"
            required
            value={myOperatorKey}
            onChange={(e) => setMyOperatorKey(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="myPublicKey">Public Key:</label>
            <input
            type="text"
            id="myPublicKey"
            value={myPublicKey}
            onChange={(e) => setMyPublicKey(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="hexPrivateKey">Hex Private Key:</label>
            <input
            type="text"
            id="hexPrivateKey"
            value={hexPrivateKey}
            onChange={(e) => setHexPrivateKey(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="tokenName">Token Name:</label>
            <input
            type="text"
            id="tokenName"
            required
            value={tokenName}
            onChange={(e) => setTokenName(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="tokenSymbol">Token Symbol:</label>
            <input
            type="text"
            id="tokenSymbol"
            required
            value={tokenSymbol}
            onChange={(e) => setTokenSymbol(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="initialSupply">Initial Supply:</label>
            <input
            type="text"
            id="initialSupply"
            required
            value={initialSupply}
            onChange={(e) => setInitialSupply(e.target.value)}
            />
        </div>
        <div>
          <label htmlFor="imageUpload">Upload Image:</label>
          <input
            type="file"
            id="imageUpload"
            accept="image/*"
            onChange={handleImageChange}
          />
        </div>
        <button type="submit">Submit</button>
        </form>
    </div>
     
  );
}

export default AddConfig;
