import React, { useState, useEffect } from "react";
import { Route, Routes, useNavigate } from "react-router-dom";
import './App.css';
import Home from "./Components/Home";
import UserAdd from "./Components/UserAdd";
import UserList from "./Components/UserList";
import UserEdit from "./Components/UserEdit";
import SendAmount from "./Components/SendAmount";
import TransactionList from "./Components/TransactionList";
import Login from "./Components/Login";
import Signup from "./Components/Signup";
import Profile from "./Components/Profile";
import Config from "./Components/Config";
import AddConfig from "./Components/AddConfig";
import EditConfig from "./Components/EditConfig";

function App() {
  const [user, setUser] = useState();
  const navigate = useNavigate();

  useEffect(() => {
    const loggedInUser = localStorage.getItem("user");
    if (loggedInUser) {
      const foundUser = JSON.parse(loggedInUser);
      setUser(foundUser);
    }
  }, []);

  return (
    <div>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/signup" element={<Signup />} />
        <Route path="/" element={<Home />} />
        <Route path="/user-add" element={<UserAdd />} />
        <Route path="/user-list" element={<UserList />} />
        <Route path="/transaction-list" element={<TransactionList />} />
        <Route path="/send-amount/:id" element={<SendAmount />} />
        <Route path="/user-edit/:id" element={<UserEdit />} />
        <Route path="/profile/:id" element={<Profile />} />
        <Route path="/config" element={<Config />} />
        <Route path="/add-config" element={<AddConfig />} />
        <Route path="/edit-config/:id" element={<EditConfig />} />
      </Routes>
    </div>
  );
}

export default App;
