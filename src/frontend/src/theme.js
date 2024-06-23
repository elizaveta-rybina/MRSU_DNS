import { createTheme } from "@mui/material/styles";
import { createContext, useMemo, useState } from "react";

//color design tokens
export const tokens = (mode) => ({
  ...(mode === "dark"
    ? {
        grey: {
          100: "#e0e0e0",
          200: "#c2c2c2",
          300: "#a3a3a3",
          400: "#858585",
          500: "#666666",
          600: "#525252",
          700: "#3d3d3d",
          800: "#292929",
          900: "#141414",
        },

        primary: {
          100: "#d9dcdc",
          200: "#b3b8b8",
          300: "#8d9595",
          400: "#677171",
          500: "#202323",
          600: "#343e3e",
          700: "#272f2f",
          800: "#1a1f1f",
          900: "#0d1010",
        },

        goldAccent: {
          100: "#fdfce5",
          200: "#fbf9ca",
          300: "#faf5b0",
          400: "#f8f295",
          500: "#f6ef7b",
          600: "#c5bf62",
          700: "#948f4a",
          800: "#626031",
          900: "#313019",
        },

        pinkAccent: {
          100: "#f2d1e7",
          200: "#e5a4cf",
          300: "#d876b6",
          400: "#cb499e",
          500: "#be1b86",
          600: "#98166b",
          700: "#721050",
          800: "#4c0b36",
          900: "#26051b",
        },

        purpleAccent: {
          100: "#e2d9eb",
          200: "#c4b2d6",
          300: "#a78cc2",
          400: "#8965ad",
          500: "#6c3f99",
          600: "#56327a",
          700: "#41265c",
          800: "#2b193d",
          900: "#160d1f",
        },
      }
    : {
        grey: {
          100: "#141414",
          200: "#292929",
          300: "#3d3d3d",
          400: "#525252",
          500: "#666666",
          600: "#858585",
          700: "#a3a3a3",
          800: "#c2c2c2",
          900: "#e0e0e0",
        },

        primary: {
          100: "#0d1010",
          200: "#1a1f1f",
          300: "#272f2f",
          400: "#f2f0f0",
          500: "#202323",
          600: "#677171",
          700: "#8d9595",
          800: "#b3b8b8",
          900: "#d9dcdc",
        },

        goldAccent: {
          100: "#313019",
          200: "#626031",
          300: "#948f4a",
          400: "#c5bf62",
          500: "#f6ef7b",
          600: "#f8f295",
          700: "#faf5b0",
          800: "#fbf9ca",
          900: "#fdfce5",
        },

        pinkAccent: {
          100: "#26051b",
          200: "#4c0b36",
          300: "#721050",
          400: "#98166b",
          500: "#be1b86",
          600: "#cb499e",
          700: "#d876b6",
          800: "#e5a4cf",
          900: "#f2d1e7",
        },

        purpleAccent: {
          100: "#160d1f",
          200: "#2b193d",
          300: "#41265c",
          400: "#56327a",
          500: "#6c3f99",
          600: "#8965ad",
          700: "#a78cc2",
          800: "#c4b2d6",
          900: "#e2d9eb",
        },
      }),
});

export const themeSettings = (mode) => {
  const colors = tokens(mode);
  return {
    palette: {
      mode: mode,
      ...(mode === "dark"
        ? {
            primary: {
              main: colors.primary[500],
            },
            secondary: {
              main: colors.goldAccent[500],
            },
            natural: {
              dark: colors.grey[700],
              main: colors.grey[500],
              light: colors.grey[100],
            },
            background: {
              default: colors.primary[500],
            },
          }
        : {
            primary: {
              main: colors.primary[100],
            },
            secondary: {
              main: colors.goldAccent[500],
            },
            natural: {
              dark: colors.grey[700],
              main: colors.grey[500],
              light: colors.grey[100],
            },
            background: {
              default: "#fcfcfc",
            },
          }),
    },
    typography: {
      fontFamily: ["Open Sans", "sans-serif"].join(","),
      fontSize: 12,
      h1: {
        fontFamily: ["Open Sans", "sans-serif"].join(","),
        fontSize: 40,
      },
      h2: {
        fontFamily: ["Open Sans", "sans-serif"].join(","),
        fontSize: 32,
      },
      h3: {
        fontFamily: ["Open Sans", "sans-serif"].join(","),
        fontSize: 24,
      },
      h4: {
        fontFamily: ["Open Sans", "sans-serif"].join(","),
        fontSize: 20,
      },
      h5: {
        fontFamily: ["Open Sans", "sans-serif"].join(","),
        fontSize: 16,
      },
      h6: {
        fontFamily: ["Open Sans", "sans-serif"].join(","),
        fontSize: 14,
      },
    },
  };
};

// context for color mode
export const ColorModeContext = createContext({
  toggleColorMode: () => {},
});

export const useMode = () => {
  const [mode, setMode] = useState("dark");

  const colorMode = useMemo(
    () => ({
      toggleColorMode: () =>
        setMode((prev) => (prev === "light" ? "dark" : "light")),
    }),
    []
  );

  const theme = useMemo(() => createTheme(themeSettings(mode)), [mode]);
  return [theme, colorMode];
};
