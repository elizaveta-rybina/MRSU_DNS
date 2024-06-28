/*
 * @property int $id
 * @property string $name
 * @property int $ttl
 * @property int $timeout
 * @property string $soa
 * @property string $admin
 * @property int $serial
 * @property int $refresh
 * @property int $retry
 * @property int $expire
 * @property int $minimum
 * @property int $created_at
 * @property int $updated_at
 */

export const mockDataRecord = [
  {
    id: 0,
    domainId: 1,
    type: "A",
    value: "192.189.123.0",
    priority: 10,
    ttl: 86400,
  },
  {
    id: 1,
    domainId: 1,
    type: "A",
    value: "192.189.123.0",
    priority: 10,
    ttl: 86400,
  },
  {
    id: 3,
    domainId: 1,
    type: "MX",
    value: "sffs",
    priority: 10,
    ttl: 86400,
  },
  {
    id: 4,
    domainId: 1,
    type: "CNAME",
    value: "svsvsv",
    priority: 10,
    ttl: 86400,
  },
  {
    id: 5,
    domainId: 1,
    type: "A",
    value: "cssscscs",
    priority: 10,
    ttl: 86400,
  },
  {
    id: 6,
    domainId: 1,
    type: "A",
    value: "194.139.125.0",
    priority: 10,
    ttl: 86400,
  },
];

export const mockDataDomain = [
  {
    id: 0,
    name: "mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 1,
    name: "vestnik.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 2,
    name: "rim.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 3,
    name: "v.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 4,
    name: "ep.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 5,
    name: "led.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 6,
    name: "ino.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
];

export const fileZona = {
  zone: "example.com",
  ttl: 3600,
  records: [
    {
      type: "SOA",
      name: "example.com",
      ttl: 3600,
      primary: "ns1.example.com",
      admin: "admin.example.com",
      serial: 2024062801,
      refresh: 7200,
      retry: 3600,
      expire: 1209600,
      minimum: 3600,
    },
    {
      type: "NS",
      name: "example.com",
      ttl: 3600,
      value: "ns1.example.com",
    },
    {
      type: "NS",
      name: "example.com",
      ttl: 3600,
      value: "ns2.example.com",
    },
    {
      type: "A",
      name: "example.com",
      ttl: 3600,
      value: "192.0.2.1",
    },
    {
      type: "A",
      name: "www.example.com",
      ttl: 3600,
      value: "192.0.2.1",
    },
    {
      type: "CNAME",
      name: "mail.example.com",
      ttl: 3600,
      value: "example.com",
    },
    {
      type: "MX",
      name: "example.com",
      ttl: 3600,
      priority: 10,
      value: "mail.example.com",
    },
    {
      type: "TXT",
      name: "example.com",
      ttl: 3600,
      value: "v=spf1 include:example.com ~all",
    },
  ],
};
