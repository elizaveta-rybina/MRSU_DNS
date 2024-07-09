import { Box, Typography, useTheme } from "@mui/material";
import { DataGrid } from "@mui/x-data-grid";
import { createSelector } from "@reduxjs/toolkit";
import React, { useState } from "react";
import { useSelector } from "react-redux";
import { tokens } from "../../theme";

const domainSelector = createSelector(
  (state) => state.domain,
  (domain) => domain
);

const Domains = ({ title }) => {
  const { domains } = useSelector(domainSelector);
  const [rows, setRows] = React.useState([]);

  React.useEffect(() => {
    setRows(domains);
  }, [domains]);

  const columns = [
    { field: "id", headerName: "ID" },
    {
      field: "name",
      headerName: "Имя домена",
      flex: 1,
      cellClassName: "name-column--cell",
    },
    {
      field: "admin",
      headerName: "Редактор",
      type: "string",
      flex: 1,
    },
    {
      field: "dateUpdated",
      headerName: "Дата последнего изменения",
      type: "dateTime",
      flex: 1,
    },
  ];

  const [sortModel, setSortModel] = useState([
    {
      field: "dateUpdated", // Название колонки для сортировки
      sort: "desc", // Порядок сортировки: 'asc' или 'desc'
    },
  ]);

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
          sortModel={sortModel} // Устанавливаем начальную модель сортировки
          onSortModelChange={(model) => setSortModel(model)} // Обработчик изменений модели сортировки
        />
      </Box>
    </Box>
  );
};

export default Domains;
