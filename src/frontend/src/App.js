import { ThemeProvider } from "@emotion/react";
import { CssBaseline } from "@mui/material";
import { Route, Routes } from "react-router-dom";
// import Bar from "./scenes/bar";
// import Calendar from "./scenes/calendar/calendar";
// import Contacts from "./scenes/contacts";
import Dashboard from "./scenes/dashboard";
// import FAQ from "./scenes/faq";
// import Form from "./scenes/form";
// import Geography from "./scenes/geography";
import Sidebar from "./scenes/global/Sidebar";
import Topbar from "./scenes/global/Topbar";
// import Invoices from "./scenes/invoices";
// import Line from "./scenes/line";
import Domain from "./scenes/domains";
import Record from "./scenes/records";
import Zona from "./scenes/zonas";
import User from "./scenes/users"
import { ColorModeContext, useMode } from "./theme";

function App() {
  const [theme, colorMode] = useMode();

  return (
    <ColorModeContext.Provider value={colorMode}>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        <div className="app">
          <Sidebar />
          <main className="content">
            <Topbar />
            <Routes>
              <Route path="/" element={<Dashboard />} />
              <Route path="/domain" element={<Domain />} />
              <Route path="/zonas" element={<Zona />} />
              <Route path="/records/:id" element={<Record />} />
              <Route path='/users' element={<User />} />
              {/* <Route path="/invoices" element={<Invoices />} />
              <Route path="/form" element={<Form />} />
              <Route path="/bar" element={<Bar />} />
              <Route path="/pie" element={<Pie />} />
              <Route path="/line" element={<Line />} />
              <Route path="/faq" element={<FAQ />} />
              <Route path="/calendar" element={<Calendar />} />
              <Route path="/geography" element={<Geography />} /> */}
            </Routes>
          </main>
        </div>
      </ThemeProvider>
    </ColorModeContext.Provider>
  );
}

export default App;
