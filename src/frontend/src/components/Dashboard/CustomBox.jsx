import { Box, Icon, Typography, useTheme } from "@mui/material";
import React from "react";
import { Link } from "react-router-dom";
import { tokens } from "../../theme";

const CustomBox = ({ mr, icon, title, link }) => {
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);
  return (
    <Box
      sx={{
        borderRadius: "30px",
        display: "flex",
        justifyContent: "center",
        flexDirection: "column",
        textAlign: "center",
        bgcolor: colors.primary[400],
        // 50% ширины контейнера минус отступы
        mr,
        [theme.breakpoints.up("sm")]: {
          flex: "calc(40% - 8px)",
        },
        [theme.breakpoints.up("md")]: {
          flex: "calc(40% - 8px)",
        },
        [theme.breakpoints.up("lg")]: {
          flex: "calc(50% - 8px)",
        },
      }}
    >
      <Icon
        sx={{
          display: "none",
          margin: "0 auto",
          bgcolor: "background.default",
          //TODO
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          [theme.breakpoints.up("sm")]: {
            display: "block",
            width: 50,
            height: 50,
            borderRadius: "15px", //TODO
          },
          [theme.breakpoints.up("md")]: {
            display: "block",
            width: 60,
            height: 60,
            borderRadius: "18px", //TODO
          },
          [theme.breakpoints.up("lg")]: {
            alignItems: "center",
            display: "block",
            width: 80,
            height: 80,
            borderRadius: "18px", //TODO
          },
        }}
      >
        {icon}
      </Icon>
      <Typography
        sx={{
          [theme.breakpoints.up("sm")]: {
            variant: "h6",
          },
          [theme.breakpoints.up("md")]: {
            variant: "h5",
          },
          [theme.breakpoints.up("lg")]: {
            variant: "h4",
          },
        }}
        mt={2}
        color={colors.goldAccent[300]}
      >
        Управление
      </Typography>
      <Typography
        variant="h3"
        color={colors.grey[100]}
        fontWeight="bold"
        margin="10px 0"
      >
        <Link
          style={{
            color: colors.primary[200], // Цвет текста ссылки
            textDecoration: "none",
            transition: "color 0.3s ease", // Плавное изменение цвета
          }}
          to={link}
        >
          {title}
        </Link>
      </Typography>
    </Box>
  );
};

export default CustomBox;
