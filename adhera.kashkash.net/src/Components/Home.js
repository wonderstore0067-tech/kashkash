import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import Swal from "sweetalert2";
import Header from "./Header";

function Home() {
  const [user, setUser] = useState();
  const [totalUser, setTotalUser] = useState();
  const [totalTransaction, setTotalTransaction] = useState();
  const [totalAmount, setTotalAmount] = useState();
  const [myDebit, setMyDebit] = useState();
  const [myCredit, setMyCredit] = useState();
  const navigate = useNavigate();


  useEffect(() => {
    const loggedInUser = localStorage.getItem("user");
    if (loggedInUser) {
      const foundUser = JSON.parse(loggedInUser);
      setUser(foundUser);
    }
    else{
      navigate('/login');
    }
  }, [navigate]);

  useEffect(() => {

    // Fetch the session value from the API endpoint
    fetch("https://adhera.kashkash.net/src/backend/home.php", {
      method: "POST",
      body: JSON.stringify(user),
    })
    .then((response) =>  response.json())
    .then((data) => {
      console.log(data);
      setTotalUser(data.totalUsers);
      setTotalTransaction(data.totalTransactions);
      setTotalAmount(data.totalAmount);
      setMyDebit(data.myDebit);
      setMyCredit(data.myCredit);
      // Handle the received JSON data
    })
    .catch((error) => {
      console.log(error);
      // Handle the error (e.g., show error message to the user)
    });

  }, [user]);

  return (
    <div>
      <Header />

      <div className="container mt-5">
        <div className="row">
          <div className="col-sm-4">
            <div className="card bg-danger d-flex justify-content-center align-items-center p-5" onClick={(e) => navigate("/user-list")}>
              <span className="fw-bold text-white">Total Users</span>
              <span className="text-white mt-2">{totalUser}</span>
            </div>
          </div>
          <div className="col-sm-4">
            <div className="card bg-info d-flex justify-content-center align-items-center p-5" onClick={(e) => navigate("/transaction-list")}>
              <span className="fw-bold text-white">Total Transactions</span>
              <span className="text-white mt-2">{totalTransaction}</span>
            </div>
          </div>
          <div className="col-sm-4">
            <div className="card bg-secondary d-flex justify-content-center align-items-center p-5" onClick={(e) => navigate("/transaction-list")}>
              <span className="fw-bold text-white">Total Amount</span>
              <span className="text-white mt-2">{totalAmount}</span>
            </div>
          </div>
        </div>
        <div className="row mt-3">
        <div className="col-sm-4">
            <div className="card bg-success d-flex justify-content-center align-items-center p-5" onClick={(e) => navigate("/transaction-list")}>
              <span className="fw-bold text-white">My Debit</span>
              <span className="text-white mt-2">{myDebit ? myDebit : 0}</span>
            </div>
          </div>
          <div className="col-sm-4">
            <div className="card bg-danger d-flex justify-content-center align-items-center p-5" onClick={(e) => navigate("/transaction-list")}>
              <span className="fw-bold text-white">My Credit</span>
              <span className="text-white mt-2">{myCredit ? myCredit : 0}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Home;
