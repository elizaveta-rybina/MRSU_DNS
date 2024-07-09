import { ThemeProvider } from "@emotion/react";
import { CssBaseline } from "@mui/material";
import Main from "./components/App/Main";
import SignInUp from "./components/App/SignInUp";
import { ColorModeContext, useMode } from "./theme";

function App() {
  const [theme, colorMode] = useMode();

  const token = true;

  return (
    <ColorModeContext.Provider value={colorMode}>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        {token === true ? <Main /> : <SignInUp />}
      </ThemeProvider>
    </ColorModeContext.Provider>
  );
}

export default App;
