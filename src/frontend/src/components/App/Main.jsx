import { Route, Routes } from "react-router-dom"
import Dashboard from "../../scenes/dashboard"
import Domain from "../../scenes/domains"
import Sidebar from "../../scenes/global/Sidebar"
import Topbar from "../../scenes/global/Topbar"
import Record from "../../scenes/records"
import User from "../../scenes/users"
import Zona from "../../scenes/zonas"

function Main() {
  return (
    <div className="app">
      <Sidebar />
      <main className="content">
        <Topbar />
        <Routes>
          <Route path="/main" element={<Dashboard />} />
          <Route path="/domain" element={<Domain />} />
          <Route path="/zonas" element={<Zona />} />
          <Route path="/records/:id" element={<Record />} />
          <Route path="/users" element={<User />} />
        </Routes>
      </main>
    </div>
  );
}

export default Main;
