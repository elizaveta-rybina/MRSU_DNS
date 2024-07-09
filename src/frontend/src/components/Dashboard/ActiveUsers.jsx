import { Box, Typography, useTheme } from "@mui/material";
import { DataGrid } from "@mui/x-data-grid";
import { createSelector } from "@reduxjs/toolkit";
import { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { tokens } from "../../theme";

const usersSelector = createSelector(
  (state) => state.user,
  (user) => user
);

const ActiveUsers = ({ title }) => {
  const { users } = useSelector(usersSelector);
  const [rows, setRows] = useState([]);
  const [sortModel, setSortModel] = useState([
    {
      field: "status",
      sort: "desc",
    },
  ]);

  useEffect(() => {
    // Создаем новый массив, чтобы избежать мутации users
    const sortedUsers = [...users].sort((a, b) => b.status - a.status);
    setRows(sortedUsers);
  }, [users]);

  const columns = [
    {
      field: "first_name",
      headerName: "Имя",
      flex: 1,
    },
    {
      field: "last_name",
      headerName: "Фамилия",
      type: "string",
      flex: 1,
    },
    {
      field: "status",
      headerName: "В сети",
      type: "boolean",
      flex: 1,
    },
  ];

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);
  return (
    <Box sx={{}}>
      <Typography
        variant="h2"
        color={colors.grey[100]}
        fontWeight="bold"
        margin="10px 0"
      >
        {title}
      </Typography>
      <Box
        my="20px"
        height="100%"
        sx={{
          "& .actions": {
            color: "text.secondary",
          },
          "& .textPrimary": {
            color: "text.primary",
          },
          "& .MuiCheckbox-root": {
            color: `${colors.goldAccent[300]} !important`,
          },
        }}
      >
        <DataGrid
          rows={rows}
          columns={columns}
          sortModel={sortModel}
          onSortModelChange={(model) => setSortModel(model)}
          autoHeight
        />
      </Box>
    </Box>
  );
};

export default ActiveUsers;
