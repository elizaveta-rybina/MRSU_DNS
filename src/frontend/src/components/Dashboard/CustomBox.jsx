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
        // "& .MuiDataGrid-root": {
        //   border: colors.primary[500],
        // },
        // border: "1px solid",
        borderRadius: "30px",
        display: "flex",
        justifyContent: "center",
        flexDirection: "column",
        textAlign: "center",
        bgcolor: colors.primary[400],
        flex: "calc(50% - 8px)", // 50% ширины контейнера минус отступы
        mr, // Отступ справа
      }}
    >
      <Icon
        sx={{
          display: "none",
          [theme.breakpoints.up("sm")]: {
            display: "block",
            margin: "0 auto",
            borderRadius: "25px", //TODO
            bgcolor: "background.default",
            width: 100,
            height: 100,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
          },
        }}
      >
        {icon}
      </Icon>
      <Typography mt={2} variant="h5" color={colors.goldAccent[300]}>
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
