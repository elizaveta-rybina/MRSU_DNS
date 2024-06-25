import AddIcon from "@mui/icons-material/Add";
import CancelIcon from "@mui/icons-material/Close";
import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import EditOutlinedIcon from "@mui/icons-material/EditOutlined";
import SaveIcon from "@mui/icons-material/Save";
import { Box, useTheme } from "@mui/material";
import Button from "@mui/material/Button";
import { DataGrid, GridActionsCellItem, GridRowModes } from "@mui/x-data-grid";
import * as React from "react";
import { useEffect, useState } from "react";
import { useLocation, useParams } from "react-router-dom";
import Header from "../../components/Header";
import { mockDataRecord } from "../../data/mockData";
import { tokens } from "../../theme";
import { Utils } from "../../utils/handleClick";

function EditToolbar(props) {
  const { setRows, setRowModesModel, filteredRows } = props;

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const handleClick = () => {
    const maxId = filteredRows.reduce((max, row) => (row.id > max ? row.id : max), 0);
    console.log(maxId);
    const id = maxId === 0 ? 0 : maxId + 1;
    setRows((oldRows) => [
      ...oldRows,
      { id, type: "", value: "", priority: 0, ttl: 0, domainId: Number(useParams().id),  isNew: true },
    ]);
    setRowModesModel((oldModel) => ({
      ...oldModel,
      [id]: { mode: GridRowModes.Edit, fieldToFocus: "type" },
    }));
  };

  const location = useLocation();
  const { domainName } = location.state || {};

  return (
    <Box>
      <Header title="DNS-записи" subtitle={domainName} />
      <Button
        color="primary"
        startIcon={<AddIcon />}
        onClick={handleClick}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100],
        }}
      >
        Добавить запись
      </Button>
    </Box>
  );
}

const Record = () => {
  const [rows, setRows] = React.useState(mockDataRecord);
  const [rowModesModel, setRowModesModel] = React.useState({});
  const {
    handleCancelClick,
    handleDeleteClick,
    handleEditClick,
    handleRowModesModelChange,
    handleSaveClick,
    processRowUpdate,
    handleRowEditStop,
  } = Utils(rows, setRows);

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const { id } = useParams();
  const [filteredRows, setFilteredRows] = useState([]);

  useEffect(() => {
    const filtered = rows.filter((row) => row.domainId === Number(id));
    setFilteredRows(filtered);
  }, [id]);

  const columns = [
    { field: "id", headerName: "ID" },
    {
      field: "type",
      headerName: "Тип",
      type: "string",
      flex: 1,
      editable: true,
    },
    {
      field: "value",
      headerName: "Значение",
      flex: 1,
      type: "string",
      editable: true,
      cellClassName: "name-column--cell",
    },
    {
      field: "priority",
      headerName: "Приоритет",
      type: "number",
      flex: 1,
      editable: true,
    },
    {
      field: "ttl",
      headerName: "Время жизни",
      type: "number",
      flex: 1,
      editable: true,
    },
    {
      field: "actions",
      type: "actions",
      headerName: "Действия",
      width: 100,
      cellClassName: "actions",
      getActions: ({ id }) => {
        const isInEditMode = rowModesModel[id]?.mode === GridRowModes.Edit;

        if (isInEditMode) {
          return [
            <GridActionsCellItem
              icon={<SaveIcon />}
              label="Save"
              sx={{
                color: "primary.main",
              }}
              onClick={() => handleSaveClick(id)} // Используем стрелочную функцию
            />,
            <GridActionsCellItem
              icon={<CancelIcon />}
              label="Cancel"
              className="textPrimary"
              onClick={() => handleCancelClick(id)} // Используем стрелочную функцию
              color="inherit"
            />,
          ];
        }

        return [
          <GridActionsCellItem
            icon={<EditOutlinedIcon />}
            label="Edit"
            className="textPrimary"
            onClick={() => handleEditClick(id)} // Используем стрелочную функцию
            color="inherit"
          />,
          <GridActionsCellItem
            icon={<DeleteOutlinedIcon />}
            label="Delete"
            onClick={() => handleDeleteClick(id)} // Используем стрелочную функцию
            color="inherit"
          />,
        ];
      },
    },
  ];

  return (
    <Box
      m="15px"
      sx={{
        "& .MuiDataGrid-root": {
          border: colors.goldAccent[300],
        },
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
        checkboxSelection
        rows={filteredRows}
        columns={columns}
        editMode="row"
        rowModesModel={rowModesModel}
        onRowModesModelChange={handleRowModesModelChange}
        onRowEditStop={handleRowEditStop}
        processRowUpdate={processRowUpdate}
        slots={{
          toolbar: EditToolbar,
        }}
        slotProps={{
          toolbar: { setRows, setRowModesModel, filteredRows },
        }}
      />
    </Box>
  );
};

export default Record;
