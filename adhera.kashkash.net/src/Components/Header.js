import React, { useState } from 'react';
import { NavLink, Link, useNavigate } from "react-router-dom";

const Header = () => {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [user, setUser] = useState();
  const navigate = useNavigate();

  const profile = sessionStorage.getItem('user');
  let id = 0;
  let admin = 0;
  if(profile)
  {
    id = JSON.parse(profile).data.id;
    admin = JSON.parse(profile).data.is_admin;
  }


  // logout the user
  const handleLogout = () => {
    setUser({});
    setUsername("");
    setPassword("");
    localStorage.clear();
    sessionStorage.clear();
      setTimeout(() => {
            window.location.href = '/login';
        }, 500);
  };


  return (
    <>
      <nav className='h2 text-white'>Hedera App</nav>

      <div className='header'>
        <NavLink to="/" className={(navData) => (navData.isActive ? "active" : "")}>Home</NavLink>
        <NavLink to="/user-add" className={(navData) => (navData.isActive ? "active" : "")}>Add User</NavLink>
        <NavLink to="/user-list" className={(navData) => (navData.isActive ? "active" : "")}>User List</NavLink>
        <NavLink to="/transaction-list" className={(navData) => (navData.isActive ? "active" : "")}>Transaction List</NavLink>
        <Link to={`/profile/${id}`} className={(navData) => (navData.isActive ? "active" : "")}>
            My Profile
        </Link>
        {admin==1 ? (
          <NavLink to="/config" className={(navData) => (navData.isActive ? "active" : "")}>Setting</NavLink>
        ) : null}
        <Link onClick={handleLogout}>Logout</Link>
      </div>
    </>
  )
}

export default Header;
