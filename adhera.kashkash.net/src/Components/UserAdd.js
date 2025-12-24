import React, { useState } from "react";
import Swal from "sweetalert2";
import Header from "./Header";

function UserAdd() {
  const [newAccountId, setNewAccountId] = useState("");
  const [newAccountPrivateKey, setNewAccountPrivateKey] = useState("");
  const [name, setName] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();

    const formData = {
      newAccountId,
      newAccountPrivateKey,
      name,
    };

    // https://adhera.kashkash.net/src/backend/add_user.php
    fetch("https://adhera.kashkash.net/src/backend/add_user.php", {
      method: "POST",
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.status === "success") {
          Swal.fire("Success", data.message, "success");
          setTimeout(() => {
                window.location.href = '/user-list';
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
  };

  return (
      <div>
        <Header />
        {/* <div className="top-btn">
        <Link to="/user-list" className="btn btn-danger m-2">
            User List
        </Link>
        <Link to="/transaction-list" className="btn btn-danger m-2">
            Transaction List
        </Link>
        </div> */}
        <form className="form-container mt-3" onSubmit={handleSubmit}>
        <h2 className="text-center">Add New User</h2>
        <div>
            <label htmlFor="newAccountId">New Account ID:</label>
            <input
            type="text"
            id="newAccountId"
            value={newAccountId}
            onChange={(e) => setNewAccountId(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="newAccountPrivateKey">New Account Private Key:</label>
            <input
            type="text"
            id="newAccountPrivateKey"
            value={newAccountPrivateKey}
            onChange={(e) => setNewAccountPrivateKey(e.target.value)}
            />
        </div>
        <div>
            <label htmlFor="name">Name:</label>
            <input
            type="text"
            id="name"
            value={name}
            onChange={(e) => setName(e.target.value)}
            />
        </div>
        <button type="submit">Submit</button>
        </form>
    </div>
     
  );
}

export default UserAdd;
