import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import { useTheme } from "@mui/material";
import Avatar from "@mui/material/Avatar";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import Checkbox from "@mui/material/Checkbox";
import FormControlLabel from "@mui/material/FormControlLabel";
import Grid from "@mui/material/Grid";
import Paper from "@mui/material/Paper";
import TextField from "@mui/material/TextField";
import Typography from "@mui/material/Typography";
import { styled } from "@mui/system";
import * as React from "react";
import { Link } from "react-router-dom";
import { tokens } from "../../theme";
import { useAuthenticateUser } from '../../api/fetch';

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
  [theme.breakpoints.down("sm")]: {
    width: "100%", // на маленьких экранах ширина 100%
  },
}));

export default function SignInSide({ setToken }) {
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);
  const { mutate: authenticateUser, isLoading, isError } = useAuthenticateUser();

  const handleSubmit = (event) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const credentials = {
      email: data.get("email"),
      password: data.get("password"),
    };
    authenticateUser(credentials, {
      onSuccess: (data) => {
        console.log('User authenticated:', data);
        localStorage.setItem('token', data.token);
        setToken(data.token);
      },
      onError: (error) => {
        console.error('Authentication failed:', error);
      },
    });
  };

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
        sm={8}
        md={6}
        lg={5}
        xl={4}
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
            mx: 2,
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            [theme.breakpoints.up("md")]: {
              mx: 4, // Отступы для md и больше
            },
          }}
        >
          <Avatar
            sx={{
              display: "none",
              [theme.breakpoints.up("sm")]: {
                display: "block",
                m: 2,
                bgcolor: "secondary.main",
                width: 60,
                height: 60,
                display: "flex",
                alignItems: "center",
              },
            }}
          >
            <LockOutlinedIcon sx={{ fontSize: 30 }} />
          </Avatar>

          <Typography
            color={colors.primary[100]}
            variant="h1"
            component="h1"
            sx={{
              fontSize: "h2.fontSize", // Используем размер шрифта для h1 из темы по умолчанию
              [theme.breakpoints.up("lg")]: {
                fontSize: "h1.fontSize", // Изменяем размер шрифта для h4 на больших экранах
              },
            }}
          >
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
              disabled={isLoading}
            >
              Войти
            </Button>
            {isError && <Typography color="error">Ошибка аутентификации</Typography>}
            <Grid container spacing={2} alignItems="center">
              <Grid item xs={12} sm={6} sx={{ textAlign: "left" }}>
                <Typography variant="body2" component="span">
                  <Link
                    to="/foggotpsw"
                    style={{
                      color: colors.primary[200], // Цвет текста ссылки
                      textDecoration: "underline", // Подчеркивание при наведении
                      transition: "color 0.3s ease", // Плавное изменение цвета
                    }}
                    className="custom-link"
                  >
                    Забыли пароль?
                  </Link>
                </Typography>
              </Grid>
              <Grid
                item
                xs={12}
                sm={6}
                sx={{
                  textAlign: "left",
                  [theme.breakpoints.up("sm")]: {
                    textAlign: "right",
                  },
                }}
              >
                <Typography variant="body2" component="span">
                  <Link
                    to="/signup"
                    style={{
                      color: colors.primary[200], // Цвет текста ссылки
                      textDecoration: "underline", // Подчеркивание при наведении
                      transition: "color 0.3s ease", // Плавное изменение цвета
                    }}
                    className="custom-link"
                  >
                    {"Нет аккаунта? Зарегистрируйтесь!"}
                  </Link>
                </Typography>
              </Grid>
            </Grid>
          </Box>
        </Box>
      </Grid>
    </Grid>
  );
}
