import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import { useTheme } from "@mui/material";
import Avatar from "@mui/material/Avatar";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import Checkbox from "@mui/material/Checkbox";
import FormControlLabel from "@mui/material/FormControlLabel";
import Grid from "@mui/material/Grid";
import Link from "@mui/material/Link";
import Paper from "@mui/material/Paper";
import TextField from "@mui/material/TextField";
import Typography from "@mui/material/Typography";
import { styled } from "@mui/system";
import * as React from "react";
import { tokens } from "../../theme";

const CustomTextField = styled(TextField)(({ theme, colors }) => ({
  "& label.Mui-focused": {
    color: colors.primary[100], // цвет текста лейбла при фокусе
  },
  "& .MuiOutlinedInput-root": {
    "& fieldset": {
      borderColor: colors.primary[200],
    },
    "&:hover fieldset": {
      borderColor: colors.primary[100],
    },
    "&.Mui-focused fieldset": {
      borderColor: colors.primary[100],
    },
    "& .MuiInputBase-input": {
      color: colors.primary[100], // Цвет текста
      backgroundColor: "transparent", // Прозрачный фон
      "&:-webkit-autofill": {
        WebkitBoxShadow: "0 0 0 100px transparent inset", // Убирает цвет фона для автозаполнения
        WebkitTextFillColor: colors.primary[100], // Цвет текста для автозаполнения
        transition: "background-color 5000s ease-in-out 0s",
      },
    },
  },
}));

export default function SignInSide() {
  const handleSubmit = (event) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    console.log({
      email: data.get("email"),
      password: data.get("password"),
    });
  };

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  return (
    <Grid
      container
      component="main"
      sx={{
        height: "80vh",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
      }}
    >
      <Grid
        item
        xs={10}
        sm={7}
        md={4}
        component={Paper}
        bgcolor={colors.primary[400]}
        elevation={2}
        sx={{
          borderRadius: 2,
          display: "flex",
          flexDirection: "column",
          justifyContent: "center",
          alignItems: "center",
          padding: 2,
        }}
      >
        <Box
          sx={{
            my: 4,
            mx: 4,
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
          }}
        >
          <Avatar
            sx={{ m: 2, bgcolor: "secondary.main", width: 60, height: 60 }}
          >
            <LockOutlinedIcon />
          </Avatar>

          <Typography color={colors.primary[100]} component="h1" variant="h1">
            Вход
          </Typography>
          <Box
            component="form"
            noValidate
            onSubmit={handleSubmit}
            sx={{ mt: 1 }}
          >
            <CustomTextField
              colors={colors}
              margin="normal"
              required
              fullWidth
              id="email"
              label="Электронная почта"
              name="email"
              autoComplete="email"
              autoFocus
            />
            <CustomTextField
              colors={colors}
              margin="normal"
              required
              fullWidth
              name="password"
              label="Пароль"
              type="password"
              id="password"
              autoComplete="current-password"
            />
            <FormControlLabel
              control={
                <Checkbox
                  value="remember"
                  sx={{
                    color: colors.primary[200],
                    "&.Mui-checked": { color: colors.primary[100] },
                  }}
                />
              }
              label="Запомнить меня"
              sx={{
                color: colors.primary[200],
                "&:hover": { color: colors.primary[100] },
                transition: "color 0.3s ease",
              }}
            />
            <Button
              type="submit"
              fullWidth
              variant="contained"
              sx={{ mt: 2, mb: 2, p: 1.5 }}
            >
              Войти
            </Button>
            <Grid container>
              <Grid item xs>
                <Link
                  href="#"
                  variant="body2"
                  sx={{
                    color: colors.primary[200],
                    textDecoration: "underline",
                    "&:hover": { color: colors.primary[100] },
                    transition: "color 0.3s ease",
                  }}
                >
                  Забыли пароль?
                </Link>
              </Grid>
              <Grid item>
                <Link
                  href="#"
                  variant="body2"
                  sx={{
                    color: colors.primary[200],
                    textDecoration: "underline",
                    "&:hover": { color: colors.primary[100] },
                    transition: "color 0.3s ease",
                  }}
                >
                  {"Нет аккаунта? Зарегистрируйтесь!"}
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Box>
      </Grid>
    </Grid>
  );
}
