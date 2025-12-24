import React, { useEffect, useState } from "react";
// import { BrowserRouter as Router, Link, Route, Routes } from "react-router-dom";
import { Client, PrivateKey, AccountId, TokenUpdateTransaction, TokenType, TokenSupplyType, TokenAssociateTransaction, TokenInfoQuery, TokenMintTransaction, AccountBalanceQuery } from "@hashgraph/sdk";
import Swal from "sweetalert2";
import Header from "./Header";
import { useParams, useNavigate } from "react-router-dom";

function EditConfig() {

    const [tokenName, setTokenName] = useState("");
    const [tokenSymbol, setTokenSymbol] = useState("");
    const [tokenNo, setTokenNo] = useState("");
    const [initialSupply, setInitialSupply] = useState("");
    const [myOperatorId, setMyOperatorId] = useState("");
    const [myOperatorKey, setMyOperatorKey] = useState("");
    const [myPublicKey, setMyPublicKey] = useState("");
    const [hexPrivateKey, setHexPrivateKey] = useState("");

    const [tokenType, setTokenType] = useState("");
    const [decimal, setDecimal] = useState("");
    const [adminKey, setAdminKey] = useState("");
    const [freezeKey, setFreezeKey] = useState("");
    const [defaultFrauzen, setDefaultFrauzen] = useState("");
    const [wipeKey, setWipeKey] = useState("");
    const [supplyManage, setSupplyManage] = useState("");

    const { id } = useParams();

    useEffect(() => {
        fetch(`https://adhera.kashkash.net/src/backend/edit_config.php?id=${id}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            setTokenName(data[0].token_name);
            setTokenSymbol(data[0].token_symbol);
            setInitialSupply(data[0].initial_supply);
            setTokenNo(data[0].token_no);
            setMyOperatorId(data[0].account_id);
            setMyOperatorKey(data[0].private_key);
            setMyPublicKey(data[0].public_key);
            setHexPrivateKey(data[0].hex_private_key);

            setTokenType(data[0].token_type || '');
            setDecimal(data[0].decimal || 0);
            setAdminKey(data[0].public_key);
            setFreezeKey(data[0].freeze_key || '');
            setDefaultFrauzen(data[0].default_frauzen || '');
            setWipeKey(data[0].wipe_key || '');
            setSupplyManage(data[0].supply_manage || '');
            
        })
        .catch((error) => {
            console.error(error);
        });
    }, [id]);


    const navigate = useNavigate();

    const handleSubmit = (e) => {
        e.preventDefault();
        

        const updateFungibleToken = async (client, operatorId, operatorKey, tokenId) => {
          try {
            // Convert the trimmed private key to a PrivateKey object
            const privateKeyObject = PrivateKey.fromString(adminKey);
            const freezeKeyObject = PrivateKey.fromString(freezeKey);
            const wipeKeyObject = PrivateKey.fromString(wipeKey);

            // Modify token attributes
            const updatedTokenTx = await new TokenUpdateTransaction()
              .setTokenId(tokenNo)
              .setTokenName(tokenName)
              .setTokenSymbol(tokenSymbol)
              .setSupplyKey(operatorKey)
              .setWipeKey(wipeKeyObject)
              .setFreezeKey(freezeKeyObject)
              .setFreezeDefault(true)
              .freezeWith(client);

            // Sign the transaction with the token adminKey and the token treasury account private key
            const signedTx = await updatedTokenTx.sign(privateKeyObject);

            // Execute the transaction
            const txResponse = await signedTx.execute(client);

            // Request the receipt of the transaction
            const receipt = await txResponse.getReceipt(client);

            // Get the transaction consensus status
            const transactionStatus = receipt.status.toString();

            console.log("The transaction consensus status is " + transactionStatus);
            // console.log(`Token updated successfully. Receipt: ${receipt}`);

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
                // Handle the response from the backend
                if (data.status === "success") {
                Swal.fire("Success", data.message, "success");
    
                setTimeout(() => {
                    // Redirect to the user-list route
                    navigate("/config");
                }, 2500);
                } else {
                Swal.fire("Error", data.message, "error");
                }
            })
            .catch((error) => {
                console.error(error);
                // Handle any errors
                Swal.fire("Error", "An error occurred", "error");
            });
        
          } catch (error) {
            console.error("Error updating token:", error);
          }
        };

        const checkAndCreateFungibleToken = async () => {
          try {
            // Initialize the client
            const operatorId = AccountId.fromString(myOperatorId);
            const operatorKey = PrivateKey.fromString(myOperatorKey);
            const client = Client.forTestnet().setOperator(operatorId, operatorKey);
        
            // Replace with the token ID you want to check
            // const tokenIdToCheck = tokenNo; // Replace with the token ID you want to check
            // const tokenInfoQuery = new TokenInfoQuery().setTokenId(tokenIdToCheck);
        
            // const tokenInfo = await tokenInfoQuery.execute(client);
            // const tokenId = tokenInfo.tokenId.toString();
        
            // console.log(tokenId)
            await updateFungibleToken(client, operatorId, operatorKey, tokenNo);
        
          } catch (error) {
            console.error("Error:", error);
          }
        };

        
        checkAndCreateFungibleToken();

        

    };

    return (
        <div>
          <Header />
          <form className="form-container mt-3" onSubmit={handleSubmit}>
            <h2 className="text-center">Edit Coin</h2>
            <div>
              <label htmlFor="tokenName">Token Name:</label>
              <input
                type="text"
                id="tokenName"
                value={tokenName} // Set the value directly from the state, not from the user object
                onChange={(e) => setTokenName(e.target.value)}
              />
            </div>
            <div>
              <label htmlFor="tokenSymbol">Token Symbol:</label>
              <input
                type="text"
                id="tokenSymbol"
                value={tokenSymbol} // Set the value directly from the state, not from the user object
                onChange={(e) => setTokenSymbol(e.target.value)}
              />
            </div>
            <div>
              <label htmlFor="initialSupply">Initial Supply:</label>
              <input
                type="text"
                id="initialSupply"
                value={initialSupply} // Set the value directly from the state, not from the user object
                onChange={(e) => setInitialSupply(e.target.value)}
              />
            </div>
            <div>
              <label htmlFor="tokenNo">Token No:</label>
              <input
                type="text"
                id="tokenNo"
                value={tokenNo} // Set the value directly from the state, not from the user object
                onChange={(e) => setTokenNo(e.target.value)}
              />
            </div>
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
              required
              value={myPublicKey}
              onChange={(e) => setMyPublicKey(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="hexPrivateKey">Hex Private Key:</label>
              <input
              type="text"
              id="hexPrivateKey"
              required
              value={hexPrivateKey}
              onChange={(e) => setHexPrivateKey(e.target.value)}
              />
          </div>

          <div>
              <label htmlFor="tokenType">Token Type:</label>
              <input
              type="text"
              id="tokenType"
              value={tokenType || 'FUNGIBLE COMMON'}
              onChange={(e) => setTokenType(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="decimal">Decimal:</label>
              <input
              type="text"
              id="decimal"
              value={decimal || '10'}
              onChange={(e) => setDecimal(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="adminKey">Admin Key:</label>
              <input
              type="text"
              id="adminKey"
              value={adminKey || myPublicKey}
              onChange={(e) => setAdminKey(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="freezeKey">Freeze Key:</label>
              <input
              type="text"
              id="freezeKey"
              value={freezeKey || ''}
              onChange={(e) => setFreezeKey(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="defaultFrauzen">Default Frauzen:</label>
              <input
              type="text"
              id="defaultFrauzen"
              readOnly={true}
              value={defaultFrauzen || 'Yes'}
              onChange={(e) => setDefaultFrauzen(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="wipeKey">Wipe Key:</label>
              <input
              type="text"
              id="wipeKey"
              value={wipeKey || ''}
              onChange={(e) => setWipeKey(e.target.value)}
              />
          </div>
          <div>
              <label htmlFor="supplyManage">Supply Manage:</label>
              <input
              type="text"
              id="supplyManage"
              value={supplyManage || ''}
              onChange={(e) => setSupplyManage(e.target.value)}
              />
          </div>

            <button type="submit">Submit</button>
          </form>
        </div>
      );
}

export default EditConfig;
