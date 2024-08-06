import { ThemeProvider } from "@emotion/react";
import { CssBaseline } from "@mui/material";
import Main from "./components/App/Main";
import SignInUp from "./components/App/SignInUp";
import { ColorModeContext, useMode } from "./theme";
import { useState, useEffect } from "react";
import {jwtDecode} from 'jwt-decode'; // Используйте именованный импорт

function App() {
  const [theme, colorMode] = useMode();
  const [token, setToken] = useState(localStorage.getItem('token') || false);

  const checkTokenExpiration = () => {
    const token = localStorage.getItem('token');
    if (token) {
      try {
        const decodedToken = jwtDecode(token);
        const isExpired = decodedToken.exp * 1000 < Date.now();
        if (isExpired) {
          localStorage.removeItem('token');
          setToken(false);
        } else {
          setToken(token);
        }
      } catch (error) {
        console.error('Invalid token:', error);
        localStorage.removeItem('token');
        setToken(false);
      }
    } else {
      setToken(false);
    }
  };

  useEffect(() => {
    checkTokenExpiration();
    const interval = setInterval(checkTokenExpiration, 60000); // Проверка каждые 60 секунд

    return () => clearInterval(interval);
  }, []);

  return (
    <ColorModeContext.Provider value={colorMode}>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        {token ? <Main /> : <SignInUp setToken={setToken} />}
      </ThemeProvider>
    </ColorModeContext.Provider>
  );
}

export default App;
