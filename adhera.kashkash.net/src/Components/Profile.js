import React, { useEffect, useState } from "react";
// import { BrowserRouter as Router, Link, Route, Routes } from "react-router-dom";
import Swal from "sweetalert2";
import Header from "./Header";
import { useParams, useNavigate } from "react-router-dom";

function Profile() {
  const [newAccountId, setNewAccountId] = useState("");
  const [newAccountPrivateKey, setNewAccountPrivateKey] = useState("");
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");

    const { id } = useParams();

    useEffect(() => {
        fetch(`https://adhera.kashkash.net/src/backend/single_user.php?id=${id}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            setNewAccountId(data[0].account_id); // Set the newAccountId state
            setNewAccountPrivateKey(data[0].private_key); // Set the newAccountPrivateKey state
            setName(data[0].name); // Set the name state
            setEmail(data[0].email);
        })
        .catch((error) => {
            console.error(error);
        });
    }, [id]);


    const navigate = useNavigate();

    const handleSubmit = (e) => {
        e.preventDefault();

        // Create an object with the form data
        const formData = {
        newAccountId,
        newAccountPrivateKey,
        name,
        email,
        id
        };

        // Send the form data to the backend
        fetch("https://adhera.kashkash.net/src/backend/update_profile.php", {
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
                navigate("/user-list");
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
          <form className="form-container mt-3" onSubmit={handleSubmit}>
            <h2 className="text-center">Profile</h2>
            <div>
              <label htmlFor="name">Name:</label>
              <input
                type="text"
                id="name"
                value={name} // Set the value directly from the state, not from the user object
                onChange={(e) => setName(e.target.value)}
              />
            </div>
            <div>
              <label htmlFor="email">Email:</label>
              <input
                type="text"
                id="email"
                value={email} // Set the value directly from the state, not from the user object
                onChange={(e) => setName(e.target.value)}
              />
            </div>
            <div>
              <label htmlFor="newAccountId">Account ID:</label>
              <input
                type="text"
                id="newAccountId"
                value={newAccountId} // Set the value directly from the state, not from the user object
                onChange={(e) => setNewAccountId(e.target.value)}
              />
            </div>
            <div>
              <label htmlFor="newAccountPrivateKey">New Account Private Key:</label>
              <input
                type="text"
                id="newAccountPrivateKey"
                value={newAccountPrivateKey} // Set the value directly from the state, not from the user object
                onChange={(e) => setNewAccountPrivateKey(e.target.value)}
              />
            </div>
            <button type="submit">Submit</button>
          </form>
        </div>
      );
}

export default Profile;
